<?php

namespace App\Fsp;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

abstract class FspProvider
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
     * @param string|UriInterface $uri
     * @param array $options
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request($method, $uri = '', array $options = [])
    {
        $body = $options[RequestOptions::BODY] ?? [];
        $headers = $options[RequestOptions::HEADERS] ?? [];
        $request = new Request($method, $uri, $headers, $body);
        // $request = $request->withUri(new Uri(implode('/', [env('MOBILE_MONEY_URL'), $uri])));
        $request = $request->withoutHeader('host');

        unset($options[RequestOptions::BODY], $options[RequestOptions::HEADERS]);

        return $this->getClient()->send($request, $options);
    }
}
