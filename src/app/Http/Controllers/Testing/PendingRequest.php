<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class PendingRequest
{
    /**
     * @var callable[]
     */
    protected $mapRequestCallbacks = [];

    /**
     * @var callable[]
     */
    protected $mapResponseCallbacks = [];

    /**
     * @var Response|null
     */
    protected $response;

    /**
     * PendingRequest constructor.
     *
     * @param Response|null $response
     */
    public function __construct(Response $response = null)
    {
        $this->response = $response;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function mapRequest(callable $callback)
    {
        $this->mapRequestCallbacks[] = $callback;
        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function mapResponse(callable $callback)
    {
        $this->mapResponseCallbacks[] = $callback;
        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return PromiseInterface
     */
    public function transfer(RequestInterface $request)
    {
        return $this->buildClient()->sendAsync($request);
    }

    /**
     * @return Client
     */
    protected function buildClient()
    {
        return new Client([
            'handler' => $this->buildHandlerStack(),
        ]);
    }

    /**
     * @return HandlerStack
     */
    protected function buildHandlerStack()
    {
        $stack = new HandlerStack();

        if ($this->response) {
            $stack->setHandler(new MockHandler([$this->response]));
        } else {
            $stack->setHandler(new CurlHandler());
        }

        foreach ($this->mapRequestCallbacks as $mapRequestCallback) {
            $stack->push(Middleware::mapRequest($mapRequestCallback));
        }

        foreach ($this->mapResponseCallbacks as $mapResponseCallback) {
            $stack->push(Middleware::mapResponse($mapResponseCallback));
        }

        return $stack;
    }
}
