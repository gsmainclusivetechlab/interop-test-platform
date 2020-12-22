<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Testing\Handlers\MapRequestHandler;
use App\Http\Controllers\Testing\Handlers\MapResponseHandler;
use App\Http\Controllers\Testing\Handlers\SendingFulfilledHandler;
use App\Http\Controllers\Testing\Handlers\SendingRejectedHandler;
use App\Models\Session;
use App\Models\TestResult;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

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
        $response = null;
        $options = [];
        if ($this->simulateRequest) {
            $response = $this->testResult->testStep->response->withSubstitutions(
                $this->session->environments()
            );

            $response = new Response(
                $response->status(),
                $response->headers(),
                $response->body()
            );
        }

        $targetComponent = $this->session
            ->components()
            ->find($this->testResult->testStep->target_id);

        if ($targetComponent && $targetComponent->pivot->use_encryption) {
//            $uri = $this->request->getUri()->withScheme('https');
//            $this->request = $this->request->withUri($uri);

            $options = [
//                'verify' => $targetComponent->pivot->certificate->getFullPath(),
//                'verify' => false,
//                'cert' => ['/var/www/html/user.crt', 'pass'],
//                'ssl_key' => ['/var/www/html/user.key', 'pass']
            ];
        }

        return (new PendingRequest($response))
            ->mapRequest(new MapRequestHandler($this->testResult))
            ->mapResponse(new MapResponseHandler($this->testResult))
            ->transfer($this->request, $options)
            ->then(
                new SendingFulfilledHandler($this->testResult, $this->session)
            )
            ->otherwise(new SendingRejectedHandler($this->testResult))
            ->wait();
    }
}
