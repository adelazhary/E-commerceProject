<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
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
            return Limit::perMinute(5)->by($request->ip());
        });
        RateLimiter::for('create-product', function ($request) {
            return Limit::perMinute(5)->response(function () {
                return response()->json(['message' => 'Too many requests'], 429);
            });
        });
        RateLimiter::for('delete-product', function ($request) {
            return Limit::perMinute(5)->response(function () {
                return response('Too many requests', 429)->header('Content-Type', 'text/plain');
            });
        });
    }
}
