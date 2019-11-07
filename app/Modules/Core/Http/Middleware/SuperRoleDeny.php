<?php

namespace App\Modules\Core\Http\Middleware;

use Closure;

class SuperRoleDeny
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
        if ($request->role->name == 'super') {
            return back()
                ->withErrors([
                    'deny' => __('Nobody can do that.'),
                ]);
        }

        return $next($request);
    }
}
