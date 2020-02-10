<?php

namespace App\Fsp\Providers;

use App\Fsp\FspProvider;
use Psr\Http\Message\ResponseInterface;

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
     * @param array $options
     * @return ResponseInterface
     */
    public function storeQuotation(array $options = [])
    {
        return $this->getClient()->post("{$this->baseUri}/quotations", $options);
    }
}
