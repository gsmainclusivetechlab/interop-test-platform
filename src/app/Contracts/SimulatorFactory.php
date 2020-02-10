<?php

namespace App\Contracts;

interface SimulatorFactory
{
    /**
     * @param string $driver
     */
    public function driver($driver = null);
}

