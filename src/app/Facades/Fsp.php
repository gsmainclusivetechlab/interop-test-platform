<?php

namespace App\Facades;

use App\Contracts\FspFactory;
use Illuminate\Support\Facades\Facade;

class Fsp extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FspFactory::class;
    }
}

