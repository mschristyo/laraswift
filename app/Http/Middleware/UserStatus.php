<?php

namespace App\Http\Middleware;

use Closure;

class UserStatus
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
        if (auth()->check() && auth()->user()->status =='banned') {
            auth()->logout();
            $message = 'Your account has been suspended! Please contact administrator.';
            return redirect()->route('login')->with('failed', $message)->with('fail', $message);
        }
        return $next($request);
    }
}
