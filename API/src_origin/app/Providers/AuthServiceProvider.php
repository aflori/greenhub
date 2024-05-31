<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserRightPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserRightPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(fn(object $notifiable, string $token) => config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}");

        Gate::define('user_check', fn(User $user) => Auth::check());

        Gate::define('is_admin', fn(User $user) => $user->role === 'admin');

    }
}
