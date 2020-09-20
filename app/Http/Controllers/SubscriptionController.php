<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Subscriptions;
use Symfony\Component\Intl\Currencies;
use Carbon\Carbon;
use App\PaymentMethod as PaymentMethod;
use App\Stripe\StripeCustomer;
use App\Stripe\StripePlan;
use App\StripePlan as DBStripePlan;
use UxWeb\SweetAlert\SweetAlert;
use \Stripe\Exception\AuthenticationException;
use \Stripe\Exception\ApiConnectionException;
use \Stripe\Exception\InvalidRequestException;
use \Stripe\Exception\ApiErrorException;

class SubscriptionController extends Controller
{
    protected $stripeCustomer;
    protected $stripePlan;
    protected $stripeDBPlan;

    public function __construct()
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth','web','2fa']);

        $this->stripeCustomer = new StripeCustomer;
        $this->stripePlan = new StripePlan;
        $this->stripeDBPlan = new DBStripePlan;
        $this->subscriptions = new Subscriptions;
    }

    public function index()
    {
        if (!setting('stripe_status')) {
            return redirect()->back()->with('failed', 'Payment is not active, Contact Admin!')->with('failed', 'Payment is not active, Contact Admin!') ;
        }

        if (!auth()->user()->stripe_id) {
            return $this->stripePlan();
        }

        $subscription = $this->stripeCustomer->subscription(auth()->user()->stripe_id);

        if ($subscription) {
            /**
             * Redirect to trial
             * @var String status
             */
            if ($subscription->status == 'trialing') {
                return $this->trial($subscription);
            }

            /**
             * Redirect to active
             * @var String status
             */
            if ($subscription->status == 'active') {
                return $this->active($subscription);
            }
        }

        return $this->stripePlan();
    }

    private function active($subscription)
    {
        $plan = [
                'name' => auth()->user()->fullname,
                'plan_name' => $subscription->plan->nickname,
                'amount' => $subscription->plan->amount,
                'start_date' => $subscription->current_period_start,
                'end_date' => $subscription->current_period_end,
                'interval' => $subscription->plan->interval,
                'interval_count' => $subscription->plan->interval_count,
                'invoiceId' => $subscription->latest_invoice,
                'status' => $subscription->status,
                'subscriptionId' => $subscription->id,
            ];

        return view('subscription.active', [
                'plan' => $plan,
            ]);
    }

    private function trial($subscription)
    {
        $plan = [
                'name' => auth()->user()->fullname,
                'plan_name' => $subscription->plan->nickname,
                'amount' => 0,
                'start_date' => Carbon::parse($subscription->current_period_start)->diffForHumans(),
                'end_date' => Carbon::parse($subscription->current_period_end)->diffForHumans(),
                'interval' => $subscription->plan->interval,
                'interval_count' => $subscription->plan->interval_count,
                'invoiceId' => $subscription->latest_invoice,
                'status' => $subscription->status,
                'subscriptionId' => $subscription->id,
            ];

        return view('subscription.trial', [
                'plan' => $plan,
            ]);
    }
    /*
     ================================== Stripe Methods ==============================
     */
    /**
    * Output plans for stripe
    *@var array
    *@return bool
    */
    private function stripePlan()
    {
        $all_plans = $this->stripeDBPlan->paginate(10);

        $plans = collect([]);

        try {
            foreach ($all_plans as $val) {
                if ($this->stripePlan->getStripePlan($val->plan_id)) {
                    foreach ($this->stripeDBPlan->wherePlanId($val->plan_id)->get() as $plan) {
                        $plans->push($plan);
                    }
                }
            }
        } catch (ApiConnectionException $th) {
            $error_msg = $th->getMessage();
        } catch (AuthenticationException $th) {
            $error_msg = $th->getMessage();
        } catch (InvalidRequestException $th) {
        } catch (ApiErrorException $th) {
            $error_msg = $th->getError();
        }
        return view('subscription.stripePlans', [
                        'plans'=> $plans,
                    ]);
    }

    /**
    * Checkout
    *@var array
    *@return boolen true|false
    */
    public function stripeCheckout($plan_id)
    {
        if (empty($plan_id) || is_null($plan_id)) {
            return redirect()->route()->back()->with('failed', 'An error occured');
        }

        $plan = $this->stripeDBPlan->wherePlanId($plan_id)->first();
        return view('subscription.stripeCheckout', [
                        'plan' => $plan,
                ]);
    }

    /**
    * Creates subscription and customer id
    *@var array
    *@return Object stripe subscription object
    */
    public function stripeSubscribe(Request $request)
    {
        $this->validate($request, [
                        'plan_id' => 'string|required',
                        'payment_method_id' => 'string|required',
                ]);

        // Create Stripe customer
        if (is_null(auth()->user()->stripe_id) || empty(auth()->user()->stripe_id)) {
            $email = (auth()->user()->email) ? auth()->user()->email : null;
            $customer = $this->stripeCustomer->createSubscriptionCustomer($email, $request->payment_method_id);
        }
        //stripe customer id
        $customer_id = auth()->user()->stripe_id;

        // Subscribe customer to a plan
        $subscription = $this->stripeCustomer->createSubscription($request->plan_id, $customer_id, $request->trial_period);

        $sub = Subscriptions::create([
                    'user_id' => auth()->user()->id,
                    'customer_id' => $subscription->customer,
                    'subscription_id' => $subscription->id,
                    'subscription_amount' => $subscription->plan->amount,
                    'plan_name' => $subscription->plan->nickname,
                'subscription_start_date' => date('Y-m-d h:i:s', $subscription->current_period_start),
                    'subscription_end_date' =>date('Y-m-d h:i:s', $subscription->current_period_end),
                    'subscription_trial_period' => date('Y-m-d h:i:s', $subscription->trial_end),
                ]);

        if ($subscription && $sub) {
            $response = "successful";
            return json_encode($response);
        } else {
            $response = "failed";
            return json_encode($response);
        }
    }

    /**
     * Fetch link pdf link for invoice
     * @param  string $invoiceId
     * @return link         			link for dpf file
     */
    public function stripeInvoice($invoiceId)
    {
        $pdf_link = $this->stripePlan->getInvoicePDF($invoiceId);
        return redirect($pdf_link);
    }

    /**
     * cancel subscription
     * @param  string $subscription_id subscription idea
     * @return [type]                  [description]
     */
    public function cancelSubscription($subscription_id)
    {
        $sub = $this->stripeCustomer->fetchSubscriptionDetails($subscription_id);

        if ($sub->status == 'trialing') {
            //Delete trial subscription from local database
            $subscription = $this->subscriptions->whereSubscriptionId($subscription_id)->first();
            if (!is_null($subscription)) {
                $subscription->delete();
            }
        }

        //Cancel subscription online
        $cancel = $sub->delete();

        if ($cancel) {
            return redirect()->back()->with('success', 'Subscription cancelled Successfully');
        } else {
            return redirect()->back()->with('failed', 'An error occured. Please try again!');
        }
    }
    /*
     ================================== Stripe Methods ==============================
     */
}
