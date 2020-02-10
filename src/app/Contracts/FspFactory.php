<?php

namespace App\Contracts;

interface FspFactory
{
    /**
     * @param string $driver
     */
    public function driver($driver = null);
}

