<?php

namespace App\Http\Middleware;

use Closure;

class VerifyEnv
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
        if (!file_exists(base_path('.env'))) {
            copy(base_path('.env.example'), base_path('.env'));
        }

        return $next($request);
    }
}
