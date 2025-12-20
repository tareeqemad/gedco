<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Models\SiteSetting;
use App\Models\FooterLink;
use App\Models\SocialLink;

/**
 * Service provider for sharing common view data across all application views.
 * Registers view composers for direction variables and footer content.
 */
class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstraps view composers to share direction variables and footer data.
     * Applies to all views using wildcard composer pattern for global access.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Share page direction (RTL/LTR) with all views
            $direction = Session::get('direction', 'rtl');
            $view->with('direction', $direction);
            $view->with('isRtl', $direction === 'rtl');
            $view->with('isLtr', $direction === 'ltr');
            
            // Share footer data with caching for performance optimization
            $footerData = Cache::remember('footer:data', 3600, function () {
                return [
                    'settings' => SiteSetting::query()->first(),
                    'services' => FooterLink::where('group','services')->where('is_active',1)->orderBy('sort_order')->get(),
                    'company'  => FooterLink::where('group','company')->where('is_active',1)->orderBy('sort_order')->get(),
                    'socials'  => SocialLink::where('is_active',1)->orderBy('sort_order')->get(),
                ];
            });
            $view->with('footerData', $footerData);
        });
    }
}
