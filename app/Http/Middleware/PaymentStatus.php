<?php

namespace App\Http\Middleware;

use Closure;

class PaymentStatus
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
        if (!setting('stripe_status')) {
            return redirect('/')->with('failed', 'Payment is not Enabled');
        }

        return $next($request);
    }
}
