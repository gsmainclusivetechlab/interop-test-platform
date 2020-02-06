<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidTraceContextException extends HttpException
{
    /**
     * InvalidTraceContextException constructor.
     * @param string|null $message
     */
    public function __construct(string $message = null)
    {
        parent::__construct(403, $message ?: 'Invalid trace context.');
    }
}
