<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

/**
 * Main application service provider for bootstrapping core application services.
 * Configures URL scheme, rate limiting, and pagination defaults globally.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registers application services in the service container.
     * Currently unused but required by service provider interface.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstraps application services and global configurations.
     * Sets HTTPS enforcement, login rate limiting, and pagination views.
     */
    public function boot(): void
    {
        // Force HTTPS scheme in production environment for security
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Configure rate limiting for login attempts to prevent brute force attacks
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            return [
                Limit::perMinute(5)->by($email.'|'.$request->ip()),
            ];
        });

        // Set default pagination views to Bootstrap 5 styling for consistency
        Paginator::defaultView('pagination.bootstrap-5');
        Paginator::defaultSimpleView('pagination.bootstrap-5');
        
        // Note: Locale is set in SetLocaleFromDirection middleware
        // Note: Direction variables are shared in ViewServiceProvider after session loads
    }
}
