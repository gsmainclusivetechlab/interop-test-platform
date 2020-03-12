<?php declare(strict_types=1);

namespace App\Testing;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class TestRequest implements Arrayable
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->request->getUri()->__toString();
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    public function getBodyAsJson()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'method' => $this->request->getMethod(),
            'uri' => $this->request->getUri()->__toString(),
            'headers' => $this->request->getHeaders(),
            'body' => $this->request->getBody()->__toString(),
            'version' => $this->request->getProtocolVersion(),
            'serverParams' => $this->request->getServerParams(),
        ];
    }

    /**
     * @param array $data
     */
    public static function fromArray(array $data)
    {
        $method = Arr::get($data, 'method');
        $uri = Arr::get($data, 'uri');
        $headers = Arr::get($data, 'headers');
        $body = Arr::get($data, 'body');
        $version = Arr::get($data, 'version');
        $serverParams = Arr::get($data, 'serverParams');
        return new self(new ServerRequest($method, $uri, $headers, $body, $version, $serverParams));
    }
}
