<?php declare(strict_types=1);

namespace App\Testing;

use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\ResponseInterface;
use function GuzzleHttp\Psr7\stream_for;

class TestResponse implements Arrayable
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * TestResponse constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function status()
    {
        return (int) $this->response->getStatusCode();
    }

    /**
     * @return bool
     */
    public function successful()
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    /**
     * @return array
     */
    public function headers()
    {
        return $this->response->getHeaders();
    }

    /**
     * @return string
     */
    public function body()
    {
        return (string) $this->response->getBody();
    }

    /**
     * @return array
     */
    public function json()
    {
        return json_decode($this->body(), true) ?? [];
    }

    /**
     * @return ResponseInterface
     */
    public function toResponse()
    {
        return $this->response;
    }

    /**
     * @return array|void
     */
    public function toArray()
    {
        return [
            'status' => $this->status(),
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
        return new self(new Response(
            $parts['status'] ?? 200,
            $parts['headers'] ?? [],
            $parts['body'] ? stream_for(json_encode($parts['body'])) : ''
        ));
    }
}
