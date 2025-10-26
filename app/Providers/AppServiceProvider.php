<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request; // <-- هذا كان ناقص

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');

            return [
                // 5 محاولات بالدقيقة لنفس (الإيميل + IP)
                Limit::perMinute(5)->by($email.'|'.$request->ip()),
            ];
        });
    }
}
