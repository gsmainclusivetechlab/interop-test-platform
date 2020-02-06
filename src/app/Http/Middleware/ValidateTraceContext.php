<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTraceContextException;
use App\Http\Headers\Traceparent;
use App\Http\Headers\Tracestate;
use Closure;
use Illuminate\Http\Request;
use InvalidArgumentException;

class ValidateTraceContext
{
    /**
     * @param Request $request
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

    /**
     * @param Request $request
     * @return bool
     */
    protected function hasValidTraceContext(Request $request)
    {
        if (!$request->headers->get(Traceparent::NAME)) {
            return false;
        }

        try {
            $tracestate = new Tracestate($request->headers->get(Tracestate::NAME));
            $traceparent = new Traceparent($request->headers->get(Traceparent::NAME));

            return !$tracestate->hasVendors() || array_search($traceparent->getParentId(), $tracestate->getVendors()) !== false;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
