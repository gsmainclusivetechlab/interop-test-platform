<?php declare(strict_types=1);

namespace App\Http\Client;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class PendingRequest extends \Illuminate\Http\Client\PendingRequest
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
     * @param callable $callback
     * @return $this
     */
    public function mapRequest(callable $callback)
    {
        return tap($this, function () use ($callback) {
            $this->mapRequestCallbacks[] = $callback;
        });
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function mapResponse(callable $callback)
    {
        return tap($this, function () use ($callback) {
            $this->mapResponseCallbacks[] = $callback;
        });
    }

    /**
     * @return \GuzzleHttp\HandlerStack
     */
    public function buildHandlerStack()
    {
        return tap(parent::buildHandlerStack(), function (HandlerStack $stack) {
            foreach ($this->mapRequestCallbacks as $mapRequestCallback) {
                $stack->push(Middleware::mapRequest($mapRequestCallback));
            }

            foreach ($this->mapResponseCallbacks as $mapResponseCallback) {
                $stack->push(Middleware::mapResponse($mapResponseCallback));
            }
        });
    }
}
