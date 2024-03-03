<?php

namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('login', function ($request) {
            return RateLimiter::perMinute(5)->by($request->ip());
        });
        RateLimiter::for('update-product', function ($request) {
            return RateLimiter::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
