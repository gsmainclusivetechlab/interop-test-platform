<?php

namespace App\Fsp\Providers;

use App\Fsp\FspProvider;
use Psr\Http\Message\ResponseInterface;

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
     * @param array $options
     * @return ResponseInterface
     */
    public function storeQuote(array $options = [])
    {
        return $this->getClient()->post("{$this->quotingServiceUri}/quotes", $options);
    }
}
