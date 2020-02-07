<?php

namespace App\Contracts;

interface Actor
{
    /**
     * @param string $driver
     */
    public function driver($driver = null);
}

