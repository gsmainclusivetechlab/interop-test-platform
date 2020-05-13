<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSessionIsPresent
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$keys
     * @return mixed|void
     */
    public function handle($request, Closure $next, ...$keys)
    {
        if (!$request->session()->has($keys)) {
            return abort(403);
        }

        return $next($request);
    }
}
