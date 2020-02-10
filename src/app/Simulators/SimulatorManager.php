<?php

namespace App\Simulators;

use App\Simulators\Providers\MobileMoneyProvider;
use App\Simulators\Providers\MojaloopProvider;
use App\Contracts\SimulatorFactory;
use Illuminate\Support\Manager;
use InvalidArgumentException;

class SimulatorManager extends Manager implements SimulatorFactory
{
    /**
     * @return MojaloopProvider
     */
    protected function createMojaloopDriver()
    {
        return new MojaloopProvider();
    }

    /**
     * @return MobileMoneyProvider
     */
    protected function createMobileMoneyDriver()
    {
        return new MobileMoneyProvider();
    }

    /**
     * @return string|void
     */
    public function getDefaultDriver()
    {
        throw new InvalidArgumentException('No default driver was specified.');
    }
}
