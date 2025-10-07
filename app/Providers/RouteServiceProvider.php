<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /** Post-login default */
    public const HOME = '/student/dashboard';

    public function boot(): void
    {
        // Define the named rate limiter used by throttle:login
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');

            // 5 attempts per minute per (email + IP)
            return [
                Limit::perMinute(5)->by($email.'|'.$request->ip()),
            ];
        });

        // Routes
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
