<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Testing\Handlers\AttachJwsHeader;
use App\Http\Controllers\Testing\Handlers\{
    MapRequestHandler,
    MapResponseHandler,
    SendingFulfilledHandler,
    SendingRejectedHandler,
};
use App\Models\Certificate;
use App\Models\Session;
use App\Models\TestResult;
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

        $result = (new PendingRequest($this->getResponse()))
            ->mapRequest(new AttachJwsHeader($this->testResult))
            ->mapRequest(new MapRequestHandler($this->testResult))
            ->mapResponse(new MapResponseHandler($this->testResult))
            ->transfer($this->request, $options)
            ->then(
                new SendingFulfilledHandler($this->testResult, $this->session)
            )
            ->otherwise(new SendingRejectedHandler($this->testResult))
            ->wait();

        if ($this->simulateRequest) {
            $delay = $this->testResult->testStep->response->withSubstitutions(
                $this->testResult->testRun->testResults,
                $this->session
            )->delay();

            sleep(is_numeric($delay) ? (int) $delay : 0);
        }

        return $result;
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
                $certificate = $targetComponent->pivot->certificate;
                $this->request = $this->request->withUri(
                    $this->request->getUri()->withScheme('https')
                );

                $options = [
                    'verify' => Storage::path($certificate->ca_crt_path),
                    'cert' => [
                        Storage::path($certificate->client_crt_path),
                        $certificate->passphrase,
                    ],
                    'ssl_key' => [
                        Storage::path($certificate->client_key_path),
                        $certificate->passphrase,
                    ],
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
            $response = $this->testResult->testStep->response;

            $response = new Response(
                $response->status(),
                $response->headers(),
                $response->body()
            );
        }

        return $response;
    }
}
