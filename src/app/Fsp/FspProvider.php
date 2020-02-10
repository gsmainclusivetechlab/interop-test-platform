<?php

namespace App\Fsp;

use GuzzleHttp\Client;

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
     * @return Client
     */
    protected function createClient()
    {
        return new Client($this->clientConfig);
    }
}
