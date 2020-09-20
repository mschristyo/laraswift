<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'payment_gateway_name', 'payment_gateway', 'image', 'status'
    ];
}
