<?php

namespace App\Http\Middleware;

use Closure;

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
        $guard = $request->post('guard', '');
        if (!empty($guard)) {
            config(['auth.guards.api.provider' => $guard]);
        }

        return $next($request);
    }
}