<?php

namespace App\Fsp;

use App\Fsp\Providers\GsmaMmProvider;
use App\Fsp\Providers\MojaloopHubProvider;
use App\Contracts\FspFactory;
use Illuminate\Support\Manager;
use InvalidArgumentException;

class FspManager extends Manager implements FspFactory
{
    /**
     * @return GsmaMmProvider
     */
    protected function createGsmaMmDriver()
    {
        $config = $this->config;
        return new GsmaMmProvider($config->get('services.gsma_mm.base_uri'));
    }

    /**
     * @return MojaloopHubProvider
     */
    protected function createMojaloopHubDriver()
    {
        $config = $this->config;
        return new MojaloopHubProvider($config->get('services.mojaloop_hub.quoting_service_uri'));
    }

    /**
     * @return string|void
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No default driver was specified.');
    }
}
