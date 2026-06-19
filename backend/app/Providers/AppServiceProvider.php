<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Passport::tokensExpireIn(Carbon::now()->addDays(7));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        Passport::personalAccessTokensExpireIn(Carbon::now()->addMonths(6));

        Passport::tokensCan([
            'user:read'=> 'Read user information',
            'user:update' => 'Update user information',
            'user:delete' => 'Delete user account',
            'message:read' => 'Read messages',
            'message:create' => 'Create messages',
            'message:update' => 'Update messages',
            'message:delete' => 'Delete messages',
        ]);

        Passport::defaultScopes([
            'user:read',
            'message:read',
        ]);
    }
}
