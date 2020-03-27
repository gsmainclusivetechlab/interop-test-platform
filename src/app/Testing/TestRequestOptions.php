<?php declare(strict_types=1);

namespace App\Testing;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use function GuzzleHttp\Psr7\stream_for;

class TestRequestOptions implements Arrayable
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var UriInterface
     */
    protected $uri;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var StreamInterface
     */
    protected $body;

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return TestRequestOptions
     */
    public function withMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return UriInterface
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param UriInterface $uri
     * @return TestRequestOptions
     */
    public function withUri(UriInterface $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return TestRequestOptions
     */
    public function withHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }


    public function withHeader($key, $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @return StreamInterface
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param StreamInterface $body
     * @return TestRequestOptions
     */
    public function withBody(StreamInterface $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return array
     */
    public function getJson()
    {
        return json_decode((string) $this->body, true) ?? [];
    }

    /**
     * @param self $options
     * @return $this
     */
    public function merge(self $options)
    {
        if ($method = $options->getMethod()) {
            $this->withMethod($method);
        }

        if ($uri = $options->getUri()) {
            $this->withUri($uri);
        }

        if ($headers = $options->getHeaders()) {
            foreach ($headers as $key => $value) {
                $this->withHeader($key, $value);
            }
        }

        if ($json = $options->getJson()) {
            foreach ($json as $key => $value) {

            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'method' => $this->getMethod(),
            'uri' => $this->getUri(),
            'headers' => $this->getHeaders(),
            'body' => $this->getBody(),
        ];
    }

    /**
     * @param RequestInterface $request
     * @return self
     */
    public static function fromRequest(RequestInterface $request)
    {
        $new = new self();
        $new->withMethod($request->getMethod());
        $new->withUri($request->getUri());
        $new->withHeaders($request->getHeaders());
        $new->withBody($request->getBody());;

        return $new;
    }

    /**
     * @param array $parts
     * @return self
     */
    public static function fromParts(array $parts)
    {
        $new = new self();

        if (isset($parts['method'])) {
            $new->withMethod($parts['method']);
        }

        if (isset($parts['uri'])) {
            $new->withUri($parts['uri']);
        }

        if (isset($parts['headers'])) {
            $new->withHeaders($parts['headers']);
        }

        if (isset($parts['body'])) {
            $new->withBody($parts['body']);
        }

        return $new;
    }
}
