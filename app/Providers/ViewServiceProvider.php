<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\SiteSetting;
use App\Models\FooterLink;
use App\Models\SocialLink;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
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
