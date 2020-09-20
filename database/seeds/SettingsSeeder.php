<?php

use Illuminate\Database\Seeder;
use Akaunting\Setting\Facade;
use Illuminate\Support\Facades\URL;

class SettingsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Application Variables
				Setting(['app_name'=> 'Lara-Swift'])->save();
				Setting(['app_dark_logo'=> ''])->save();
				Setting(['app_light_logo'=> ''])->save();
				Setting(['app_theme' => 'dark'])->save();
				Setting(['app_navbar' => '#007BFF'])->save();
				Setting(['app_sidebar' => 'light'])->save();
				Setting(['app_currency' => 'USD'])->save();

        // Payment Variables
				Setting(['stripe_key' => ''])->save();
				Setting(['stripe_secret' => ''])->save();
				Setting(['stripe_status' => 0])->save();

        // Authentication Variables
				Setting(['captcha' => 0])->save();
				Setting(['2fa' => 0])->save();
				Setting(['email_verification' => 0])->save();
    }
}
