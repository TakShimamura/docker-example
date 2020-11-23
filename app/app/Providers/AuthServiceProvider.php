<?php

namespace App\Providers;

use Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use App\Support\Auth\AuthUserProvider;
use App\Support\Auth\AuthUserGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider(AuthUserProvider::class,function($app,array $appConfig){
            return new AuthUserProvider();
        });
        Auth::extend(AuthUserGuard::class, function($app,$name,array $config){
            $request = app()->request;        
            $provider = Auth::createUserProvider($config['provider']);
            return new AuthUserGuard($provider,$request);
        });
        //
    }
}
