<?php

namespace App\Providers;

use App\Models\Department;
use App\Models\User;
use App\Policies\DepartmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Department::class => DepartmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::provider('custom_eloquent', function ($app, array $config) {
            return new \App\Providers\CustomUserProvider($app['hash'], $config['model']);
        });

        // Define gates for admin access
        Gate::define('access-admin', function (User $user) {
            return $user->isAdmin();
        });

        // Define gates for member access
        Gate::define('access-member', function (User $user) {
            return $user->isMember();
        });

        // Define gates for specific admin actions
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-departments', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-loans', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-commodities', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('view-reports', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('manage-settings', function (User $user) {
            return $user->isAdmin();
        });

        // Define gates for specific member actions
        Gate::define('apply-for-loan', function () {
            // Always allow access for now, we'll check eligibility in the controller
            return true;
        });

        Gate::define('view-own-profile', function (User $user, User $profileUser) {
            return $user->id === $profileUser->id || $user->isAdmin();
        });
    }
}
