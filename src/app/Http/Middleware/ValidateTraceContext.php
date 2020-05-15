<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\InvalidTraceContextException;
use App\Http\Headers\TraceparentHeader;
use App\Http\Headers\TracestateHeader;
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
        return $request->headers->has(TraceparentHeader::NAME);
        if (!$request->headers->get(TraceparentHeader::NAME)) {
            return false;
        }

        try {
            $tracestate = new TracestateHeader($request->headers->get(TracestateHeader::NAME));
            $traceparent = new TraceparentHeader($request->headers->get(TraceparentHeader::NAME));

            return !$tracestate->hasVendors() || array_search($traceparent->getParentId(), $tracestate->getVendors()) !== false;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
