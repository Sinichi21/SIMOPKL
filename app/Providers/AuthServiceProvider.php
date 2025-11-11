<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
// use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            // return url('/reset-password?token='.$token.'&email='.urlencode($user->email));
        });
    }

    // public function boot()
    // {
    //     $this->registerPolicies();

    //     Password::broker('users', function () {
    //         return new CustomPasswordBroker(
    //             app('auth.password.tokens'),
    //             app('auth.password.email')
    //         );
    //     });
    // }
}
