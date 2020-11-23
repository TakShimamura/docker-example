<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    

    public $bindings = [
        \App\Contracts\CustomerManagementService::class => \App\Support\Services\CustomerManager::class,
        \App\Contracts\RenderableException::class => \App\Exceptions\APIRequestException::class,
        \App\Contracts\Attempt::class => \App\Support\Facades\Attempt::class,
        \App\Contracts\RSA::class => \App\Support\Facades\RSA::class,
        \App\Contracts\JWT::class => \App\Support\Facades\JWT::class,
        \App\Contracts\User::class => \App\Support\Facades\User::class,
    ];

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
        //
    }
}
