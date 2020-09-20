<?php

namespace App\Http\Middleware;

use Closure;
use App\Stripe\StripeCustomer;

class CheckSubscription
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
        $sub = app(StripeCustomer::class);
        /**
         * Checks if user authentication and user role
         * @var [type]
         */
        if (auth()->check() && auth()->user()->getRoleNames()->first() != "admin") {

                  /**
                   * Check for users Stripe_id or Subscription_id
                   * @var string
                   */
            if (!auth()->user()->stripe_id) {
                return redirect('/')->with('failed', "You are not subscribed to any plan, hence you can't access premium content");
            }

            /**
             * Return view for trial mode
             * @var [type]
             */
            if ($sub->subscriptionStatus(auth()->user()->stripe_subscriptionId) == 'trialing') {
                $request->session()->flash('failed', 'You are current on TRIAL MODE');
                return $next($request);
            }

            /**
             * Return view for activated subscription
             * @var [type]
             */
            if ($sub->subscriptionStatus(auth()->user()->stripe_subscriptionId) == 'active') {
                return $next($request);
            }

            return redirect('/')->with('failed', "You are not autorized to access premium content");
        }

        return $next($request);
    }
}
