<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTraceContextException;
use App\Http\Headers\Traceparent;
use App\Http\Headers\Tracestate;
use Closure;
use InvalidArgumentException;

class ValidateTraceparent
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        $tracestate = new Tracestate('vendor1=00f067aa0ba902b7');
        $tracestate = $tracestate->withVendor('test', '00f067aa0ba902b5');
        $tracestate = $tracestate->withoutVendor('test');

        $traceparent = new Traceparent('00-4bf92f3577b34da6a3ce929d0e0e5544-bd5438785fe7d6cb');

        dd((string) $traceparent);

        try {
            $traceparent = new Traceparent($request->headers->get(Traceparent::NAME));
            return $next($request);
        } catch (InvalidArgumentException $e) {
            throw new InvalidTraceContextException($e->getMessage());
        }
    }
}
