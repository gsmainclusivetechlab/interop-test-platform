<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Models\TestResult;
use App\Testing\TestExecutionListener;
use App\Testing\TestSchemeLoader;
use App\Testing\TestScriptLoader;
use PHPUnit\Framework\TestResult as TestSuiteResult;
use PHPUnit\Framework\TestSuite;
use Psr\Http\Message\ResponseInterface;

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
        $this->testResult = $testResult;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function __invoke(ResponseInterface $response)
    {
        $testSuite = new TestSuite();
//        $testSuite->addTestSuite((new TestSchemeLoader())->load($this->testResult));
        $testSuite->addTestSuite((new TestScriptLoader())->load($this->testResult));
        $testSuiteResult = new TestSuiteResult();
        $testSuiteResult->addListener(new TestExecutionListener($this->testResult));
        $testSuiteResult = $testSuite->run($testSuiteResult);

        if ($testSuiteResult->wasSuccessful()) {
            $this->testResult->pass();
        } else {
            $this->testResult->fail();
        }

        if (
            $this->testResult->testStep->isLastPosition()
            ||
            !($response->getStatusCode() >= 200 && $response->getStatusCode() < 300)
        ) {
            $this->testResult->testRun->complete();
        }

        return $response;
    }
}
