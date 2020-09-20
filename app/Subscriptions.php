<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    protected $fillable = ['user_id','customer_id','subscription_id','subscription_amount',
    'subscription_start_date','subscription_end_date','subscription_trial_period','plan_name'];
}
