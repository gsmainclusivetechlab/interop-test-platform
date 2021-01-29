<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Jobs\ExecuteTestStepJob;
use App\Models\Session;
use App\Models\TestResult;
use App\Testing\TestExecutionListener;
use App\Testing\Tests\RequestMtlsValidationTest;
use App\Testing\Tests\ResponseJwsValidationTest;
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
     * @var bool
     */
    protected $simulateRequest;

    /**
     * @param TestResult $testResult
     * @param Session $session
     */
    public function __construct(
        TestResult $testResult,
        Session $session,
        $simulateRequest
    )
    {
        $this->testResult = $testResult;
        $this->session = $session;
        $this->simulateRequest = $simulateRequest;
    }

    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws NoPath
     */
    public function __invoke(ResponseInterface $response)
    {
        $testSuite = new TestSuite();
        if ($this->testResult->testStep->mtls) {
            $testSuite->addTest(
                new RequestMtlsValidationTest($this->testResult)
            );
        }
        $testSuite->addTestSuite(
            (new TestSpecLoader())->load($this->testResult)
        );
        $testSuite->addTestSuite(
            (new TestScriptLoader())->load($this->testResult)
        );
        if ($this->testResult->testStep->response->jws()) {
            $testSuite->addTest(
                new ResponseJwsValidationTest(
                    $this->testResult,
                    $this->testResult->testStep->response
                        ->withSubstitutions(
                            $this->testResult->testRun->testResults,
                            $this->testResult->session
                        )
                        ->jws()
                )
            );
        }
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

        if ($this->simulateRequest) {
            $delay = $this->testResult->testStep->response->withSubstitutions(
                $this->testResult->testRun->testResults,
                $this->session
            )->delay();

            sleep(abs(is_numeric($delay) ? (int) $delay : 0));
        }

        if (
            ($nextTestStep = $this->testResult->testStep->getNext()) &&
            !$this->session->getBaseUriOfComponent($nextTestStep->source)
        ) {
            $delay = $nextTestStep->request->withSubstitutions(
                $this->testResult->testRun->testResults,
                $this->session
            )->delay();

            ExecuteTestStepJob::dispatch(
                $this->session,
                $nextTestStep,
                $this->testResult->testRun
            )->delay(
                now()->addSeconds(
                    abs(is_numeric($delay) ? (int) $delay : 0)
                )
            );
        }

        if ($this->testResult->testStep->isLastPosition()) {
            $this->testResult->testRun->complete();
        }

        return $response;
    }
}
