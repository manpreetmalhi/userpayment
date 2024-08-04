<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;




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
    // Define a global rate limiter
    RateLimiter::for('api', function ($request) {
        // Allow 60 requests per minute per user, or per IP if the user is not authenticated
        return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
    });

    // Define a separate rate limiter for login attempts
    RateLimiter::for('login', function ($request) {
        // Allow 10 login attempts per minute per IP
        return Limit::perMinute(5)->by($request->ip());
    });
}

}