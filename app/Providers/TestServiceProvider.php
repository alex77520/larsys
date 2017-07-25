<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Payment\Client\Charge;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PaymentCharge::class, function ($app) {
            return new Charge();
        });
    }

}
