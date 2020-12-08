<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Jobs\ExecuteTestStepJob;
use App\Models\Session;
use App\Models\TestResult;
use App\Testing\TestExecutionListener;
use App\Testing\TestScriptLoader;
use App\Testing\TestSpecLoader;
use League\OpenAPIValidation\PSR7\Exception\NoPath;
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
     * @var Session
     */
    protected $session;

    /**
     * @param TestResult $testResult
     * @param Session $session
     */
    public function __construct(TestResult $testResult, Session $session)
    {
        $this->testResult = $testResult;
        $this->session = $session;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NoPath
     */
    public function __invoke(ResponseInterface $response)
    {
        $testSuite = new TestSuite();
        $testSuite->addTestSuite(
            (new TestSpecLoader())->load($this->testResult)
        );
        $testSuite->addTestSuite(
            (new TestScriptLoader())->load($this->testResult)
        );
        $testSuiteResult = new TestSuiteResult();
        $testSuiteResult->addListener(
            new TestExecutionListener($this->testResult)
        );
        $testSuiteResult = $testSuite->run($testSuiteResult);

        if ($testSuiteResult->wasSuccessful()) {
            $this->testResult->pass();
        } else {
            $this->testResult->fail();
        }

        if (
            ($nextTestStep = $this->testResult->testStep->getNext()) &&
            !$this->session->getBaseUriOfComponent($nextTestStep->source)
        ) {
            ExecuteTestStepJob::dispatch(
                $this->session,
                $nextTestStep,
                $this->testResult->testRun
            );
        }

        if ($this->testResult->testStep->isLastPosition()) {
            $this->testResult->testRun->complete();
        }

        return $response;
    }
}
