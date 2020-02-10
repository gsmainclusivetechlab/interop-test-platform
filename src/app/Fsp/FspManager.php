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
     * @return MojaloopHubProvider
     */
    protected function createMojaloopHubDriver()
    {
        return new MojaloopHubProvider();
    }

    /**
     * @return GsmaMmProvider
     */
    protected function createGsmaMmDriver()
    {
        return new GsmaMmProvider();
    }

    /**
     * @return string|void
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No default driver was specified.');
    }
}
