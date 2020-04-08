<?php

namespace App\Models\Concerns;

use App\Casts\HttpStreamCast;
use App\Casts\HttpUriCast;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

trait InteractsWithHttpRequest
{
    /**
     * @return void
     */
    protected function initializeInteractsWithHttpRequest()
    {
        $this->fillable = array_merge($this->fillable, [
            'method',
            'uri',
            'headers',
            'body',
        ]);
        $this->casts = array_merge($this->casts, [
            'uri' => HttpUriCast::class,
            'headers' => 'array',
            'body' => HttpStreamCast::class,
        ]);
    }

    /**
     * @return array
     */
    public function uriToArray()
    {
        return [
            'scheme' => $this->uri->getScheme(),
            'host' => $this->uri->getHost(),
            'port' => $this->uri->getPort(),
            'path' => $this->uri->getPath(),
            'query' => $this->uri->getQuery(),
            'fragment' => $this->uri->getFragment(),
        ];
    }

    /**
     * @return array|mixed
     */
    public function bodyToArray()
    {
        return json_decode((string) $this->body, true) ?? [];
    }

    /**
     * @return array
     */
    public function attributesToArrayRequest()
    {
        return [
            'method' => $this->method,
            'uri' => $this->uriToArray(),
            'headers' => $this->headers,
            'body' => $this->bodyToArray(),
        ];
    }

    /**
     * @param RequestInterface $request
     * @return self
     */
    public static function makeFromRequest(RequestInterface $request)
    {
        return static::make([
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'headers' => $request->getHeaders(),
            'body' => $request->getBody(),
        ]);
    }

    /**
     * @return Request
     */
    public function toRequest()
    {
        return new Request($this->method, $this->uri, $this->headers, $this->body);
    }
}
