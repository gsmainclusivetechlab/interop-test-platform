<?php

namespace App\Mixins;

use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class PsrHttpResponseMixin
{
    /**
     * @return \Closure
     */
    public function convertToPsr()
    {
        return function () {
            $psr17Factory = new Psr17Factory;
            return (new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory))->createResponse($this);
        };
    }

    /**
     * @return \Closure
     */
    public static function createFromPsr()
    {
        return function ($psr) {
            return static::createFromBase((new HttpFoundationFactory())->createResponse($psr));
        };
    }
}
