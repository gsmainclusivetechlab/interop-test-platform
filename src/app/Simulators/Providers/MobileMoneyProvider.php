<?php

namespace App\Simulators\Providers;

use App\Simulators\SimulatorProvider;

class MobileMoneyProvider extends SimulatorProvider
{
    /**
     * @param array $clientConfig
     */
    public function __construct(array $clientConfig = [])
    {
        $clientConfig = array_merge([
            'base_uri' => env('MOBILE_MONEY_URL'),
            'allow_redirects' => false,
        ], $clientConfig);
        parent::__construct($clientConfig);
    }
}
