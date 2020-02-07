<?php

namespace App\Contracts;

interface ActorFactory
{
    /**
     * @param string $driver
     */
    public function driver($driver = null);
}

