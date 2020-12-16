<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Support\Services\Auth\DieselGuardian as Guardian;


class DieselGuardianServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Guardian::class, function ($app){
            return new Guardian($app);
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
