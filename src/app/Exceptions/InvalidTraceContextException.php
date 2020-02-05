<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidTraceContextException extends HttpException
{
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(403, 'Invalid trace context.');
    }
}
