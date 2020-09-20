<?php

  namespace App\Stripe;



class StripePlan extends Stripe
{
    /**
    * Create new stripe subscription plans
    * @var string $plan_name, $plan_amount, $plan_currency, $plan_interval
    * @return object plan
    */
    public function createStripePlan($plan_name,$plan_amount,$plan_interval,$plan_intervalCount,$trial_period)
    {
        $create = $this->plan->create([
              'amount' => $plan_amount * 100,
              'currency' => config('app.currency'),
              'interval' => $plan_interval,
              'interval_count' => $plan_intervalCount,
              'trial_period_days' => $trial_period,
              'nickname' => $plan_name,
              'product' => ['name' => config('app.name')],
          ]);
        return $create;
    }

    /**
    * edit existing stripe subscription plan
    * @var string $plan_id, $plan_name, $plan_amount, $plan_currency, $plan_interval
    * @return object plan
    */
    public function updateStripePlan($plan_id,$plan_name)
    {
        $create = $this->plan->update(
          $plan_id,
          [
              'nickname' => $plan_name,
          ]);
        return $create;
    }

    /**
    * function deletes stripe subscription plan
    * @var string $plan_id
    * @return object plan
    */
    public function deleteStripePlan($plan_id)
    {
        $deletePlan = $this->plan->retrieve($plan_id);
        $deletePlan->delete();
        return $deletePlan;
    }


    /**
    * get all plans
    * @return Plans|Null
    */
    public function getStripePlans()
    {
        $plans = \Stripe\Plan::all();
        return $plans;
    }

    /**
    * Get plan by plan-id
    * @var string $plan_id
    * @return Plan|Null
    */
    public function getStripePlan($plan_id)
  	{
  		$plan = \Stripe\Plan::retrieve($plan_id);
  		return $plan;
  	}

    /**
     * Gets invoice details
     * @param  string $invoiceId
     * @return self
     */
    public function getInvoice($invoiceId)
    {
      $invoice = \Stripe\Invoice::retrieve($invoiceId);
      return $invoice;
    }

    /**
     * Generate payment receipt
     * @param  string $id invoice id
     * @return file pdf
     */
    public function getInvoicePDF($id)
    {
      $invoice = $this->getInvoice($id);
      return $invoice->invoice_pdf;
    }

    public function getBalance()
    {
      $balance = \Stripe\BalanceTransaction::all();
      return $balance;
    }

}
