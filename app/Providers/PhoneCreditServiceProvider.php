<?php

namespace App\Providers;

use App\Services\PhoneCreditService;
use Illuminate\Support\ServiceProvider;

class PhoneCreditServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('phone-credit-service', function () {
            // return new PhoneCreditService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
