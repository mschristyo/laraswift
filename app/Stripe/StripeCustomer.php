<?php
    namespace App\Stripe;

use Auth;
use Illuminate\Support\Collection;
use Carbon\Carbon;


class StripeCustomer extends Stripe
{
    /**
    * create stripe customers with payment method
    * @var string|null $email
    * @var string|null $payment_method
    */
    public function createSubscriptionCustomer($email=null,$payment_method=null)
    {
        $customer = \Stripe\Customer::create([
            'email' => $email,
            'payment_method' => $payment_method,
            'invoice_settings' => [
              'default_payment_method' => $payment_method,
            ],
        ]);

        // Update stripe customer id in users table
        $user = Auth::user();
        $user->stripe_id = $customer->id;
        $user->save();

        return $customer;
    }

    /**
    * create stripe customers with payment method
    * @var string|null $email
    * @var string|null $payment_method
    */
    public function createSubscription($plan=null,$customer=null,$trial_period=null)
    {
        $subscription = \Stripe\Subscription::create([
            'customer' => $customer,
            'items' => [
              [
                'plan' => $plan,
              ],
            ],
            'expand' => ['latest_invoice.payment_intent'],
            'trial_end' => ($trial_period > 0)? Carbon::now()->addDays($trial_period)->timestamp:null,
          ]);
          // Update stripe customer id in users table
          $user = Auth::user();
          $user->stripe_subscriptionId = $subscription->id;
          $user->save();

        return $subscription;
    }

    /**
    * Get customer object
    * @var string $customer_id
    */
    public function getCustomer($customer_id)
    {
        $customer = \Stripe\Customer::retrieve($customer_id);
        return $customer;
    }

    /**
    * Get customer object
    * @var string $customer_id
    */
    public function getCustomerPlan($customer_id)
    {
        $plan = $this->getCustomer($customer_id);
        return $customer;
    }

    /**
    * Gets customers current subscription
    * @var string $customer_id
    * @return bool true|false
    */
    public function subscription($customer_id)
    {
        $customer = \Stripe\Customer::retrieve([
          'id' => $customer_id,
        ]);
        $collection = new Collection($customer->subscriptions->data);
          return $collection->sortByDesc(function ($value) {
              return $value->created;
        })->first();
    }

    public function getSubscription($subscription_id)
    {
      $subscription = \Stripe\Subscription::retrieve($subscription_id);
      return $subscription;
    }

    /**
     * Fetch subscription object
     * @param  String $subscription_id subscription id
     * @return Object                  Subscription Object
     */
    public function fetchSubscriptionDetails($subscription_id)
    {
        $subscription = \Stripe\Subscription::retrieve($subscription_id);
        return $subscription;
    }

    /**
     * Checks status of subscription if active or cancelled
     * @param  String  $subscription_id
     * @return String   Subscription status
     */
    public function subscriptionStatus($subscription_id)
    {
        $active = $this->fetchSubscriptionDetails($subscription_id);
        return $active->status;
    }

}
