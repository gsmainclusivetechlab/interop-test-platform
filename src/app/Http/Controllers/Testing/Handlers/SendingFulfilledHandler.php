<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Testing\TestExecutionListener;
use App\Testing\TestScriptLoader;
use App\Testing\TestSpecLoader;
use PHPUnit\Framework\TestResult as TestSuiteResult;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ResponseInterface;
use SebastianBergmann\Timer\Timer;

class SendingFulfilledHandler
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
        Timer::start();
        $this->testResult = $testResult;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ResponseInterface $response)
    {
        $testSuite = new TestSuite();
        $testSuite->addTestSuite((new TestSpecLoader())->load($this->testResult));
        $testSuite->addTestSuite((new TestScriptLoader())->load($this->testResult));
        $testSuiteResult = new TestSuiteResult();
        $testSuiteResult->addListener(new TestExecutionListener($this->testResult));
        $testSuiteResult = $testSuite->run($testSuiteResult);

        if ($testSuiteResult->wasSuccessful()) {
            $this->testResult->pass(Timer::stop());
        } else {
            $this->testResult->fail(Timer::stop());
        }

        if ($this->testResult->testStep->isLastPosition()) {
            $this->testResult->testRun->complete();
        }

        return $response;
    }
}
