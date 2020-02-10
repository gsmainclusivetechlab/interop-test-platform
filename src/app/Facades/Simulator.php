<?php

namespace App\Facades;

use App\Contracts\SimulatorFactory;
use Illuminate\Support\Facades\Facade;

class Simulator extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SimulatorFactory::class;
    }
}

