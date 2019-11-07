<?php

namespace App\Modules\Core\Http\Middleware;

use Closure;

class RedirectIfBackend
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
        if ($request->user()->is_backend) {
            return redirect(config('core.backend_routes_prefix'));
        }

        return $next($request);
    }
}
