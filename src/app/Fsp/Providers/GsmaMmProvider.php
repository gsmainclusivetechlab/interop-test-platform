<?php

namespace App\Fsp\Providers;

use App\Fsp\FspProvider;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GsmaMmProvider extends FspProvider
{
    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @param string $baseUri
     * @param array $clientConfig
     */
    public function __construct($baseUri, array $clientConfig = [])
    {
        $this->baseUri = $baseUri;
        parent::__construct($clientConfig);
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param string|StreamInterface $body
     * @param array $headers
     * @return ResponseInterface
     */
    public function storeQuotation($body, array $headers = [])
    {
        return $this->getClient()->post("{$this->baseUri}/quotations", [
            RequestOptions::BODY => $body,
            RequestOptions::HEADERS => $headers,
        ]);
    }
}
