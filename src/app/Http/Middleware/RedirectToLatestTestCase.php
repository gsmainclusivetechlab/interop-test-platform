<?php

namespace App\Http\Middleware;

use App\Models\TestCase;
use Closure;

class RedirectToLatestTestCase
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $testCase = $request->testCase;
        if (($testCase instanceof TestCase) && !$testCase->isLast()) {
            return redirect()->route(
                \Route::currentRouteName(),
                $testCase->last_version->id
            );
        }

        return $next($request);
    }
}
