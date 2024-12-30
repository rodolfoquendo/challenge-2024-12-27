<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @codeCoverageIgnore
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        /**
         * In case you dont want databaseless login, remove this
         */
        $this->app['auth']->provider('auth-provider',  
            function ($app, array $config) {
                return new JWTProvider();
        });
        //
    }
}
