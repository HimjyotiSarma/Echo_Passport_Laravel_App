<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Telescope for Local debug
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Model Behaviours
        Model::preventLazyLoading(! app()->isProduction());
        Model::preventSilentlyDiscardingAttributes( ! app()->isProduction());

        // Passport Configuration
        Passport::authorizationView('auth.oauth.authorize');
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
        // Intercepting Gates
        Gate::before(function(User $user, string $ability){
            if($user->isSuperAdmin()){
                return true;
            }
            return null;
        });
    }
}
