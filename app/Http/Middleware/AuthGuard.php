<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class AuthGuard
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
        $guard = $request->input('provider', '');
        if (!empty($guard)) {
            Config::set('auth.guards.api.provider', $guard);
        }

        return $next($request);
    }
}
