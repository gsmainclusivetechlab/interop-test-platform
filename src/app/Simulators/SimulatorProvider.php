<?php

namespace App\Simulators;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\StreamInterface;

abstract class SimulatorProvider
{
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var array
     */
    protected $clientConfig = [];

    /**
     * @param array $clientConfig
     */
    public function __construct(array $clientConfig = [])
    {
        $this->clientConfig = $clientConfig;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->createClient();
        }

        return $this->client;
    }

    /**
     * @param Client $client
     * @return $this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Client
     */
    protected function createClient()
    {
        return new Client($this->clientConfig);
    }

    /**
     * @param $method
     * @param string $uri
     * @param array $headers
     * @param string|null|resource|StreamInterface $body
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $uri = '', array $headers = [], $body = null)
    {
        $request = new Request($method, $uri, $headers, $body);
        $request = $request->withoutHeader('host');

        return $this->getClient()->send($request);
    }
}
