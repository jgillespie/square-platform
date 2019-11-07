<?php

namespace App\Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if ($request->user()->is_backend) {
                return redirect(config('core.backend_routes_prefix'));
            }

            return redirect(config('core.frontend_routes_prefix'));
        }

        return $next($request);
    }
}
