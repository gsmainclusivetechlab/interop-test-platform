<?php declare(strict_types=1);

namespace App\Http\Controllers\Testing;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

class PendingRequest
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var callable[
     */
    protected $mapRequestCallbacks = [];

    /**
     * @var callable[
     */
    protected $mapResponseCallbacks = [];

    /**
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
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
     * @param callable|null $onFulfilled
     * @param callable|null $onRejected
     * @return mixed
     */
    public function send(callable $onFulfilled = null, callable $onRejected = null)
    {
        return $this->buildClient()->sendAsync($this->request)->then($onFulfilled, $onRejected)->wait();
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
        $stack->setHandler(new CurlHandler());
        $stack->push(Middleware::httpErrors());

        foreach ($this->mapRequestCallbacks as $mapRequestCallback) {
            $stack->push(Middleware::mapRequest($mapRequestCallback));
        }

        foreach ($this->mapResponseCallbacks as $mapResponseCallback) {
            $stack->push(Middleware::mapResponse($mapResponseCallback));
        }

        return $stack;
    }
}
