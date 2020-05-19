<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Testing\TestExecutionListener;
use App\Testing\TestSpecLoader;
use App\Testing\TestScriptLoader;
use PHPUnit\Framework\TestResult as TestSuiteResult;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\RequestInterface;

class BeforeSendingHandler
{
    /**
     * @var TestResult
     */
    protected $testResult;

    /**
     * @param TestResult $testResult
     */
    public function __construct(TestResult $testResult)
    {
        $this->testResult = $testResult;
    }

    /**
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function __invoke(RequestInterface $request)
    {
        app()->terminating(function () {
            $testSuite = new TestSuite();
            $testSuite->addTestSuite((new TestSpecLoader())->load($this->testResult));
            $testSuite->addTestSuite((new TestScriptLoader())->load($this->testResult));
            $testSuiteResult = new TestSuiteResult();
            $testSuiteResult->addListener(new TestExecutionListener($this->testResult));
            $testSuiteResult = $testSuite->run($testSuiteResult);

            if ($testSuiteResult->wasSuccessful()) {
                $this->testResult->pass();
            } else {
                $this->testResult->fail();
            }

            if ($this->testResult->testStep->isLastPosition()) {
                $this->testResult->testRun->complete();
            }
        });

        return $request;
    }
}
