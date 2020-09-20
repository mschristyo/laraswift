<?php
  namespace App\Stripe;

  use \Stripe\Plan;

  class Stripe
  {
    protected $plan;

    public function __construct()
    {
        \Stripe\Stripe::setApiKey(setting('stripe_secret'));
        \Stripe\Stripe::setApiVersion("2020-03-02");
        $this->plan = new Plan;
    }

  }
