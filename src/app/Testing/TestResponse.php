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
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return \string[][]
     */
    public function getHeaders()
    {
        return $this->response->getHeaders();
    }

    /**
     * @return mixed
     */
    public function getBodyAsJson()
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'status' => $this->getStatus(),
            'headers' => $this->getHeaders(),
            'body' => $this->getBodyAsJson(),
        ];
    }
}
