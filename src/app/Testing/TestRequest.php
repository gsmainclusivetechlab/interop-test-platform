<?php declare(strict_types=1);

namespace App\Testing;

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\RequestInterface;
use function GuzzleHttp\Psr7\stream_for;

class TestRequest implements Arrayable
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * TestRequest constructor.
     * @param RequestInterface $request
     */
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function method()
    {
        return $this->request->getMethod();
    }

    /**
     * @return string
     */
    public function url()
    {
        return (string) $this->request->getUri();
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->request->getUri()->getPath();
    }

    /**
     * @return array
     */
    public function headers()
    {
        return $this->request->getHeaders();
    }

    /**
     * @return string
     */
    public function body()
    {
        return (string) $this->request->getBody();
    }

    /**
     * @return array
     */
    public function json()
    {
        return json_decode($this->body(), true) ?? [];
    }

    /**
     * @return RequestInterface
     */
    public function toRequest()
    {
        return $this->request;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'method' => $this->method(),
            'uri' => $this->url(),
            'headers' => $this->headers(),
            'body' => $this->json(),
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @param array $parts
     * @return self
     */
    public static function fromParts(array $parts)
    {
        return new self(new Request(
            $parts['method'] ?? '',
            $parts['uri'] ?? '',
            $parts['headers'] ?? [],
            $parts['body'] ? stream_for(json_encode($parts['body'])) : ''
        ));
    }
}
