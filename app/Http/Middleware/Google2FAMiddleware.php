<?php
namespace App\Http\Middleware;

use App\Support\Google2FAAuthenticator;
use Closure;

class Google2FAMiddleware
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
        $authenticator = app(Google2FAAuthenticator::class)->boot($request);

        // Check if Two Factor authentication is activated
        if (setting('2fa')) {

        // Check if user is authenticated with Two factor authentication
            if ($authenticator->isAuthenticated()) {
                return $next($request);
            }
            return $authenticator->makeRequestOneTimePasswordResponse();
        } else {
            return $next($request);
        }
    }
}
