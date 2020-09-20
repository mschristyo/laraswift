<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable  = ['user_id','method'];

    /**
      * The attributes that should be mutated to dates.
      *
      * @var array
      */
    protected $dates = [
        'created_at',
        'updated_at',
        'current_period_start',
        'current_period_end',
    ];

    public function user()
    {
        return $this->belongTo('App\User');
    }

    public function getCurrentPeriodStartAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
    public function getCurrentPeriodEndAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
