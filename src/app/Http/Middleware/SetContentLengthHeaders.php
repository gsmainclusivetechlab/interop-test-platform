<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetContentLengthHeaders
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set(
            'Content-Length',
            mb_strlen($request->getContent())
        );

        return $next($request);
    }
}
