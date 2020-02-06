<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidTraceContextException extends HttpException
{
    /**
     * InvalidTraceContextException constructor.
     */
    public function __construct()
    {
        parent::__construct(403, 'Invalid trace context.');
    }
}
