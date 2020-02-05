<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTraceContextException;
use App\Http\Headers\Traceparent;
use Closure;
use Illuminate\Http\Request;

class ValidateTraceContext
{
    const HEADER_TRACEPARENT = 'traceparent';

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        if ($this->hasValidTraceContext($request)) {
            return $next($request);
        }

        throw new InvalidTraceContextException;
    }

    protected function hasValidTraceContext(Request $request)
    {
        $traceparent = new Traceparent($request->headers->get(static::HEADER_TRACEPARENT));

        dd($traceparent);
    }
}
