<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ✅ Prevent auto-login of newly created users
        Auth::shouldUse('web');

        // ✅ Define gates for role-based access
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('isUser', function (User $user) {
            return $user->role === 'user';
        });

        Gate::define('isPlayer', function (User $user) {
            return $user->role === 'player';
        });

        Gate::define('isVisitor', function (User $user) {
            return $user->role === 'visitor';
        });
    }
}
