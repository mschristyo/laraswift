<?php

namespace App\Jobs\StripeWebhooks;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;
use App\Subscriptions;
use App\User;

class SubscriptionUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;

    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle()
    {
      // you can access the payload of the webhook call with `$this->webhookCall->payload`

        $data = $this->webhookCall->payload['data']['object'];

        $user = User::where('stripe_id',$data['customer'])->first();

        if($user){
          Subscriptions::create([
              'user_id' => $user->id,
              'customer_id' => $data['customer'],
              'subscription_id' => $data['id'],
              'subscription_amount' => $data['plan']['amount'],
              'plan_name' => $data['plan']['nickname'],
              'subscription_start_date' => date('Y-m-d h:i:s', $data['current_period_start']),
              'subscription_end_date' =>date('Y-m-d h:i:s', $data['current_period_end']),
              'subscription_trial_period' => null,
          ]);
        }
    }
}
