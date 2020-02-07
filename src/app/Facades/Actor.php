<?php

namespace App\Facades;

use App\Contracts\ActorFactory;
use Illuminate\Support\Facades\Facade;

class Actor extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ActorFactory::class;
    }
}

