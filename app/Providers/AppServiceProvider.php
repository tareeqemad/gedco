<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // إلزام https في الإنتاج فقط (live server)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // تحديد معدل محاولات تسجيل الدخول
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            return [
                Limit::perMinute(5)->by($email.'|'.$request->ip()),
            ];
        });

        Paginator::defaultView('pagination.bootstrap-5');
        Paginator::defaultSimpleView('pagination.bootstrap-5');
    }
}
