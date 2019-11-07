<?php

namespace App\Modules\Core\Http\Middleware;

use Closure;

class RedirectIfFrontend
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
        if (! $request->user()->is_backend) {
            return redirect(config('core.frontend_routes_prefix'));
        }

        return $next($request);
    }
}
