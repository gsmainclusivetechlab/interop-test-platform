<?php

namespace App\Mixins;

use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class PsrRequestMixin
{
    /**
     * @return \Closure
     */
    public function covertToPsr()
    {
        return function () {
            $psr17Factory = new Psr17Factory;
            return (new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory))->createRequest($this);
        };
    }

    /**
     * @return \Closure
     */
    public static function createFromPsr()
    {
        return function ($psr) {
            return static::createFromBase((new HttpFoundationFactory())->createRequest($psr));
        };
    }
}
