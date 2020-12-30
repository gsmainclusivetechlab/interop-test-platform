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
        if ($user = auth()->user()) {
            \App::setLocale(
                $user->locale && in_array($user->locale, config('app.locales'))
                    ? $user->locale
                    : \App::getFallbackLocale()
            );
        }

        return $next($request);
    }
}
