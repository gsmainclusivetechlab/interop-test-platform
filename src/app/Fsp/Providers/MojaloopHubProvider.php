<?php

namespace App\Fsp\Providers;

use App\Fsp\FspProvider;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class MojaloopHubProvider extends FspProvider
{
    /**
     * @var string
     */
    protected $quotingServiceUri;

    /**
     * @param string $quotingServiceUri
     * @param array $clientConfig
     */
    public function __construct($quotingServiceUri, array $clientConfig = [])
    {
        $this->quotingServiceUri = $quotingServiceUri;
        parent::__construct($clientConfig);
    }

    /**
     * @return string
     */
    public function getQuotingServiceUri()
    {
        return $this->quotingServiceUri;
    }

    /**
     * @param string|StreamInterface $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function storeQuote($body, array $headers = [])
    {
        return $this->getClient()->post("{$this->quotingServiceUri}/quotes", [
            RequestOptions::BODY => $body,
            RequestOptions::HEADERS => $headers,
        ]);
    }
}
