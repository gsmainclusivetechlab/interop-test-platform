<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetJsonHeaders
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        if (!$request->isJson()) {
            $request->headers->set('Content-Type', 'application/json');
        }

        return $next($request);
    }
}
