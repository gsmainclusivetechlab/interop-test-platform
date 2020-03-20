<?php declare(strict_types=1);

namespace App\Testing;

use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\RequestInterface;

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
     * @return array
     */
    public function headers()
    {
        return collect($this->request->getHeaders())
            ->mapWithKeys(function ($values, $header) {
                return [$header => $values];
            })->all();
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
    protected function json()
    {
        return json_decode($this->body(), true);
    }

    /**
     * @return array|void
     */
    public function toArray()
    {
        return [
            'uri' => $this->url(),
            'method' => $this->method(),
            'headers' => $this->headers(),
            'body' => $this->json(),
        ];
    }
}
