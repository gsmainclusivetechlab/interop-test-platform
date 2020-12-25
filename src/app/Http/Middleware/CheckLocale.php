<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;

class CheckLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = auth()->user()) {//dd(env('LOCALE_SUPPORTED'));
            \App::setLocale($user->locale ?? \App::getFallbackLocale());
//            \App::setLocale($user->locale);
        }

        return $next($request);
    }
}
