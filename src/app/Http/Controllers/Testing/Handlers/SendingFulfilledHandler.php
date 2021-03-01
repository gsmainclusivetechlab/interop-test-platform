<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing\Handlers;

use App\Http\Client\Request;
use App\Http\Client\Response;
use App\Jobs\ExecuteTestStepJob;
use App\Models\Session;
use App\Models\TestResult;
use App\Models\TestStep;
use App\Testing\TestExecutionListener;
use App\Testing\Tests\RequestMtlsValidationTest;
use App\Testing\Tests\JwsValidationTest;
use App\Testing\TestScriptLoader;
use App\Testing\TestSpecLoader;
use Arr;
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
     * @param bool $simulateRequest
     */
    public function __construct(
        TestResult $testResult,
        Session $session,
        $simulateRequest
    ) {
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
        $isRepeat =
            $this->testResult->iteration <
                $this->testResult->testStep->repeat_max &&
            TestStep::whereKey($this->testResult->testStep->id)
                ->whereRaw('JSON_CONTAINS(?, repeat_condition)', [
                    json_encode($this->testResult->response->toArray()),
                ])
                ->exists();
        $this->testResult->update([
            'repeat' => $isRepeat,
        ]);
        $testSuiteResult = $this->getTestSuiteResult($isRepeat);

        if ($this->simulateRequest) {
            $delay = $this->testResult->testStep->response
                ->withSubstitutions(
                    $this->testResult->testRun->testResults,
                    $this->session,
                    $this->testResult->testStep
                )
                ->delay();

            sleep(abs(is_numeric($delay) ? (int) $delay : 0));
        }

        if ($testSuiteResult->wasSuccessful()) {
            $this->testResult->pass();
        } else {
            $this->testResult->fail();
        }

        $nextTestStep = $isRepeat
            ? $this->testResult->testStep
            : $this->testResult->testStep->getNext();
        if (
            $nextTestStep &&
            !$this->session->getBaseUriOfComponent($nextTestStep->source)
        ) {
            $delay = $nextTestStep->request
                ->withSubstitutions(
                    $this->testResult->testRun->testResults,
                    $this->session,
                    $this->testResult->testStep
                )
                ->delay();

            ExecuteTestStepJob::dispatch(
                $this->session,
                $nextTestStep,
                $this->testResult->testRun
            )->delay(
                now()->addSeconds(abs(is_numeric($delay) ? (int) $delay : 0))
            );
        }

        if ($this->testResult->testStep->isLastPosition() && !$isRepeat) {
            $this->testResult->testRun->complete();
        }

        return $response;
    }

    /**
     * @return \PHPUnit\Framework\TestResult
     * @throws NoPath
     */
    protected function getTestSuiteResult($isRepeat)
    {
        $testSuite = new TestSuite();
        if ($this->testResult->testStep->mtls) {
            $testSuite->addTest(
                new RequestMtlsValidationTest($this->testResult)
            );
        }
        $this->attachJWSValidation(
            $testSuite,
            $this->testResult->request,
            $this->testResult->testStep->request,
            'Request: JWS Signature'
        );
        $testSuite->addTestSuite(
            (new TestSpecLoader())->load($this->testResult)
        );
        $testSuite->addTestSuite(
            (new TestScriptLoader())->load($this->testResult, $isRepeat)
        );
        $this->attachJWSValidation(
            $testSuite,
            $this->testResult->response,
            $this->testResult->testStep->response,
            'Response: JWS Signature'
        );
        $testSuiteResult = new TestSuiteResult();
        $testSuiteResult->addListener(
            new TestExecutionListener($this->testResult)
        );

        return $testSuite->run($testSuiteResult);
    }

    /**
     * @param TestSuite $testSuite
     * @param Request|Response $requestOrResponse
     * @param Request|Response $testStepRequestOrResponse
     * @param string $title
     */
    protected function attachJWSValidation(
        TestSuite $testSuite,
        $requestOrResponse,
        $testStepRequestOrResponse,
        $title
    ) {
        if (
            ($jws = $testStepRequestOrResponse->jws()) &&
            !is_null(Arr::get($jws, 'public_key'))
        ) {
            $testSuite->addTest(
                new JwsValidationTest(
                    $requestOrResponse,
                    $testStepRequestOrResponse
                        ->withSubstitutions(
                            $this->testResult->testRun->testResults,
                            $this->testResult->session,
                            $this->testResult->testStep
                        )
                        ->jws(),
                    $title
                )
            );
        }
    }
}
