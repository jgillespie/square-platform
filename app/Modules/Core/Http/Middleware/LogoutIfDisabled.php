<?php

namespace App\Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogoutIfDisabled
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
        if (! $request->user()->is_enabled) {
            Auth::logout();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => __('Your account has been disabled.'),
                ]);
        }

        return $next($request);
    }
}
