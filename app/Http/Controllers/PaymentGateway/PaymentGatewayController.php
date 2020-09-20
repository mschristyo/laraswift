<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use URL;
use App\User;
use App\Subscriptions;
use App\Stripe\StripePlan;
use App\Stripe\StripeCustomer;
use App\StripePlan as DBStripePlan;
use \Stripe\Exception\AuthenticationException;
use \Stripe\Exception\ApiConnectionException;
use \Stripe\Exception\InvalidRequestException;
use \Stripe\Exception\ApiErrorException;
use Storage;

class PaymentGatewayController extends Controller
{
    protected $stripePlan;
    protected $stripeDBPlan;
    protected $stripeCustomer;
    protected $user;
    protected $subscriptions;

    public function __construct()
    {
        if (setting('email_verification')) {
            $this->middleware(['verified']);
        }
        $this->middleware(['auth','payment','web','permission:manage-payment-gateway','role:admin','2fa']);
        $this->stripePlan = new StripePlan;
        $this->stripeCustomer = new StripeCustomer;
        $this->stripeDBPlan = new DBStripePlan;
        $this->user = new User;
        $this->subscriptions = new Subscriptions;
    }

    public function viewStripePlans()
    {
        $all_plans = $this->stripeDBPlan->all();
        $error_msg = '';
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
        return view('paymentgateway.stripe.planIndex', [
              'plans' => $plans,
              'error_message' => $error_msg,
            ]);
    }

    public function createStripePlan()
    {
        return view('paymentgateway.stripe.planCreate');
    }

    public function storeStripePlan(Request $request)
    {
        $this->validate($request, [
              "plan_name" => 'required|regex:/^[A-Za-z0-9_.,() ]+$/|unique:stripe_plans',
              "plan_description" => 'required',
              "plan_amount" => 'required|numeric',
              "plan_interval" => 'required|string',
              "plan_intervalCount" => 'nullable|regex:/^[1-9]$/',
              "plan_trial_days" => 'nullable|min:4|numeric',
            ],[
              'plan_name.regex' => 'Invalid Entry! The plan name only allows letters',
              'plan_intervalCount.regex' => 'Invalid Entry! The  interval count only integer 1-9 is allowed',
        ]);

        $createdPlan = $this->stripePlan->createStripePlan(
            $request->plan_name,
            $request->plan_amount,
            $request->plan_interval,
            $request->plan_intervalCount,
            $request->plan_trial_period
        );

        $this->stripeDBPlan->create([
              'plan_id' => $createdPlan->id,
              'plan_name' => $createdPlan->nickname,
              'plan_description' => clean($request->plan_description),
              'plan_amount' => $createdPlan->amount,
              'plan_interval' => $createdPlan->interval,
              'plan_intervalCount' => $createdPlan->interval_count,
              'trial_period_days' => $createdPlan->trial_period_days,
            ]);
        return redirect()->back()->with('success', 'Plan Created Successfully');
    }

    public function editStripePlan($id)
    {
        $plan = $this->stripeDBPlan->wherePlanId($id)->first();
        return view('paymentgateway.stripe.planEdit', [
            'plan' => $plan,
          ]);
    }

    public function updateStripePlan(Request $request, $id)
    {
        $this->validate($request, [
            'plan_name' => '|regex:/^[A-Za-z0-9_.,() ]+$/|required|unique:stripe_plans,plan_name,'.$request->id,
            'plan_description' => 'required',
          ],[
            'plan_name.regex' => 'Invalid Entry! The plan name only allows letters',
          ]);
        $updatedPlan = $this->stripePlan->updateStripePlan($request->plan_id, $request->plan_name, $request->plan_amount, $request->plan_interval, $request->plan_intervalCount);

        $this->stripeDBPlan->wherePlanId($updatedPlan->id)->update([
            'plan_name' => $updatedPlan->nickname,
            'plan_description' => clean($request->plan_description),
            'plan_amount' => $updatedPlan->amount,
            'plan_interval' => $updatedPlan->interval,
            'plan_intervalCount' => $updatedPlan->interval_count,
          ]);

        return redirect()->back()->with('success', 'Plan Updated Successfully');
    }

    public function deleteStripePlan($plan_id)
    {
        $deletedPlan = $this->stripePlan->deleteStripePlan($plan_id);
        $this->stripeDBPlan->wherePlanId($deletedPlan->id)->delete();

        return redirect()->back()->with('success', 'Plan Deleted Successfully');
    }

    public function manageSubscribedUser()
    {
        $user = collect([]);
        $values = $this->subscriptions->all();
        $error_msg = '';

        try {
            foreach ($values as $val) {
                if ($this->stripeCustomer->getSubscription($val->subscription_id)->status == "active") {
                    $user->push([
                    'id' => $val->id,
                    'fullname'=>$this->getFullname($val->user_id),
                    'stripe_id'=>$val->subscription_id,
                    'subscription'=>$this->stripeCustomer->getSubscription($val->subscription_id),
                    'customer'=>$this->stripeCustomer->getCustomer($val->customer_id),
                  ]);
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


        return view('paymentgateway.subscribedUsers', [
              'subscriptions' => $user,
              'error_message' => $error_msg,
            ]);
    }

    public function manageIncome()
    {
        $income = [];
        $total_income = '';
        $values = $this->subscriptions->all();

        // Overrall Amount Accumulated
        $overrall_income = collect($this->subscriptions->all())->map(function ($value) {
            return $value->subscription_amount;
        })->sum();

        foreach ($values as $val) {
            $income[] = [
                'id' => $val->id,
                'payer'=>$this->getFullname($val->user_id),
                'amount'=>$val->subscription_amount,
                'date'=>$val->created_at,
                ];

            //Sum total of amount per-pagination
            $sum[] = $val->subscription_amount;
            $total_income = array_sum($sum);
        }

        return view('paymentgateway.incomeRecord', [
              'incomes' => $income,
              'total' => $total_income,
              'overrall_income' => $overrall_income,
            ]);
    }

    public function SubscriptionInvoice($invoiceId)
    {
        $pdf_link = $this->stripePlan->getInvoicePDF($invoiceId);
        return redirect($pdf_link);
    }

    /**
     * cancel subscription
     * @param  string $subscription_id subscription idea
     * @return [type]                  [description]
     */
    public function cancelSub($subscription_id)
    {
        $sub = $this->stripeCustomer->fetchSubscriptionDetails($subscription_id);

        //Delete subscription from local database
        if ($sub->status == 'trialing') {
            //Delete trial subscription from local database
            $subscription = $this->subscriptions->whereSubscriptionId($subscription_id)->first();

            if (!is_null($subscription)) {
                $subscription->delete();
            }
        }

        $cancel = $sub->delete();
        //Cancel subscription online

        if ($cancel) {
            return redirect()->back()->with('success', 'Subscription cancelled Successfully');
        } else {
            return redirect()->back()->with('failed', 'An error occured. Please try again!');
        }
    }

    private function getFullname($id)
    {
        $user = User::find($id);
        return ($user)? $user->fullname : null;
    }

    // SAMPLE CODE FOR CHECKOUT
   public function checkoutSamples()
   {
     $error = "";

     try{
       $intent = \Stripe\PaymentIntent::create([
          'amount' => 1099,
          'currency' => 'usd',
          // Verify your integration in this guide by including this parameter
          'metadata' => ['integration_check' => 'accept_a_payment'],
        ]);

      } catch(\Stripe\Exception\CardException $e) {
         $error =  $e->getError()->message;
       } catch (\Stripe\Exception\RateLimitException $e) {
          $error = 'Too many requests made to the API too quickly';
       } catch (\Stripe\Exception\InvalidRequestException $e) {
          $error = "Invalid parameters were supplied to Stripe's API";
       } catch (\Stripe\Exception\AuthenticationException $e) {
          $error = "Authentication with Stripe's API failed";
         // (maybe you changed API keys recently)
       } catch (\Stripe\Exception\ApiConnectionException $e) {
          $error = "Network communication with Stripe failed";
       } catch (\Stripe\Exception\ApiErrorException $e) {
         $error = "An error occured try again";
       } catch (Exception $e) {
         $error = "An error occured try again";
       }
       if($error){
         return redirect()->back()->with(['error'=>$error]);
       }

       return view('paymentgateway.sample.checkoutSample',[
         'secret_id' => $intent->client_secret
       ]);

   }

   public function responseCheckout(Request $request,$id)
   {
     $error = '';
     if(isset($id)){

       try{
          $payment_intent = \Stripe\PaymentIntent::retrieve($id);

          if(isset($payment_intent)){
            $status = $payment_intent->status;
          }

        } catch(\Stripe\Exception\CardException $e) {
           $error =  $e->getError()->message;
         } catch (\Stripe\Exception\RateLimitException $e) {
            $error = 'Too many requests made to the API too quickly';
         } catch (\Stripe\Exception\InvalidRequestException $e) {
            $error = "Invalid parameters were supplied to Stripe's API";
         } catch (\Stripe\Exception\AuthenticationException $e) {
            $error = "Authentication with Stripe's API failed";
           // (maybe you changed API keys recently)
         } catch (\Stripe\Exception\ApiConnectionException $e) {
            $error = "Network communication with Stripe failed";
         } catch (\Stripe\Exception\ApiErrorException $e) {
           $error = "An error occured try again";
         } catch (Exception $e) {
           $error = "An error occured try again";
         }

         if($error){
           return view('paymentgateway.sample.checkoutResponse')->with(['response'=>'failed','message'=>$error]);
         }

         if($status=='succeeded'){
            return view('paymentgateway.sample.checkoutResponse')->with(['response'=>'success','message'=>'Payment completed successfully!']);
         }

     }

     return view('paymentgateway.sample.checkoutResponse')->with(['response'=>'failed','message'=>'Payment was not successful! try again']);
   }

   public function download()
   {
        $url = storage_path('article.pdf');
        return response()->download($url);
   }
   // SAMPLE CODE FOR CHECKOUT

}
