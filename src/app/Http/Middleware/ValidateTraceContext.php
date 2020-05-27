<?php declare(strict_types=1);

namespace App\Http\Middleware;

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

        abort(403, 'Invalid trace context.');
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function hasValidTraceContext(Request $request)
    {
        if (!$request->headers->get(TraceparentHeader::NAME)) {
            return false;
        }

        try {
            $tracestate = new TracestateHeader($request->headers->get(TracestateHeader::NAME));
            $traceparent = new TraceparentHeader($request->headers->get(TraceparentHeader::NAME));

            return !$tracestate->hasVendors() || $tracestate->hasVendorWithSpanId($traceparent->getParentId());
        } catch (InvalidArgumentException $e) {
            abort(403, $e->getMessage());
        }
    }
}
