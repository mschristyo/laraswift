<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plan_name')->unique();
            $table->string('plan_id');
            $table->longText('plan_description');
            $table->string('plan_interval')->nullable();
            $table->string('plan_intervalCount')->nullable();
            $table->string('plan_logo')->nullable();
            $table->double('plan_amount', 8, 2);
            $table->string('trial_period_days')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stripe_plans');
    }
}
