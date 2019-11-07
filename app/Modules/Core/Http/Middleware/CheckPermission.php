<?php

namespace App\Modules\Core\Http\Middleware;

use Closure;

class CheckPermission
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
        $permissionName = $request->route()->getName();

        if (! $request->user()->hasPermission($permissionName)) {
            return back()
                ->withErrors([
                    'permission' => __("You don't have the required permission: ").$permissionName,
                ]);
        }

        return $next($request);
    }
}
