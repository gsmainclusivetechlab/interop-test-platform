<?php declare(strict_types=1);

namespace App\Testing;

use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\ResponseInterface;

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
        return collect($this->response->getHeaders())
            ->mapWithKeys(function ($values, $header) {
                return [$header => $values];
            })->all();
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
        return json_decode($this->body(), true);
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
}
