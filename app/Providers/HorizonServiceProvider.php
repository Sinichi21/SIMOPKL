<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class HorizonServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (! class_exists(\Laravel\Horizon\Horizon::class)) {
            return;
        }

        $this->app->register(
            \Laravel\Horizon\HorizonApplicationServiceProvider::class
        );
    }

    public function boot(): void
    {
        if (! class_exists(\Laravel\Horizon\Horizon::class)) {
            return;
        }

        \Laravel\Horizon\Horizon::auth(function ($request) {
            return true; // atur gate sesuai kebutuhan
        });
    }

    protected function gate(): void
    {
        Gate::define('viewHorizon', function ($user = null) {
            return true;
        });
    }
}
