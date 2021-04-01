<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Testing\Handlers\{
    AttachJwsHeader,
    MapRequestHandler,
    MapResponseHandler,
    SendingFulfilledHandler,
    SendingRejectedHandler
};
use App\Models\{Certificate, Session, TestResult};
use Exception;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Storage;

class ProcessPendingRequest
{
    /**
     * @var RequestInterface
     */
    protected $request;

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
     * @param RequestInterface $request
     * @param TestResult $testResult
     * @param Session $session
     * @param bool $simulateRequest
     */
    public function __construct(
        RequestInterface $request,
        TestResult $testResult,
        Session $session,
        bool $simulateRequest
    ) {
        $this->request = $request;
        $this->session = $session;
        $this->testResult = $testResult;
        $this->simulateRequest = $simulateRequest;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        $options = $this->getRequestOptions();

        return (new PendingRequest($this->getResponse()))
            ->mapRequest(
                new AttachJwsHeader(
                    $this->testResult,
                    $this->testResult->testStep->request,
                    !$this->session->getBaseUriOfComponent(
                        $this->testResult->testStep->source
                    )
                )
            )
            ->mapRequest(new MapRequestHandler($this->testResult))
            ->mapResponse(new MapResponseHandler($this->testResult))
            ->mapResponse(
                new AttachJwsHeader(
                    $this->testResult,
                    $this->testResult->testStep->response,
                    !$this->session->getBaseUriOfComponent(
                        $this->testResult->testStep->target
                    )
                )
            )
            ->transfer($this->request, $options)
            ->then(
                new SendingFulfilledHandler(
                    $this->testResult,
                    $this->session,
                    $this->simulateRequest
                )
            )
            ->otherwise(new SendingRejectedHandler($this->testResult))
            ->wait();
    }

    protected function getRequestOptions(): array
    {
        $options = [];
        if ($this->testResult->testStep->mtls) {
            $targetComponent = $this->session
                ->components()
                ->find($this->testResult->testStep->target_id);

            if ($targetComponent && $targetComponent->pivot->use_encryption) {
                /** @var Certificate $certificate */
                $certificate = $targetComponent->pivot->implicitSut
                    ? $targetComponent->pivot->implicitSut->certificate
                    : $targetComponent->pivot->certificate;

                if (!$certificate) {
                    (new SendingRejectedHandler($this->testResult))(
                        new Exception(
                            __(
                                'mTLS certificates not found. Please configure it in session settings.'
                            )
                        )
                    );
                }
                if (!$certificate->client_crt_path) {
                    (new SendingRejectedHandler($this->testResult))(
                        new Exception(
                            __(
                                'No client certificate has been uploaded. Please configure it in session settings.'
                            )
                        )
                    );
                }

                $this->request = $this->request->withUri(
                    $this->request->getUri()->withScheme('https')
                );

                $options = [
                    //'verify' => Storage::path($certificate->ca_crt_path),
                    'cert' => Storage::path($certificate->client_crt_path),
                    'ssl_key' => env('CLIENT_KEY_PATH'),
                ];
            }
        }

        return $options;
    }

    /**
     * @return Response|null
     */
    protected function getResponse()
    {
        $response = null;
        if ($this->simulateRequest) {
            $response =
                $this->testResult->iteration >
                $this->testResult->testStep->repeat_count
                    ? $this->testResult->testStep->response
                    : $this->testResult->testStep->repeat_response;

            $response = new Response(
                $response->status(),
                $response->headers(),
                $response->body()
            );
        }

        return $response;
    }
}
