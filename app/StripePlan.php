<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StripePlan extends Model
{
    protected $fillable = ['plan_id','plan_name','plan_description','plan_amount',
      'plan_logo','plan_interval','plan_intervalCount','trial_period_days'];
}
