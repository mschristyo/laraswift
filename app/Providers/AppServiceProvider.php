<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength('191');

        Blade::directive('datetime', function ($value) {
            return "<?php echo ($value)->format('M d, Y').' at '($value)->format('H:i'); ?>";
        });


    }


}
