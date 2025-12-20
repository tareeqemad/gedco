<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to set application locale based on session direction preference.
 * Automatically switches between Arabic (ar) and English (en) locales accordingly.
 */
class SetLocaleFromDirection
{
    /**
     * Processes incoming request and sets locale based on session direction.
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $direction = $request->session()->get('direction', 'rtl');
        $locale = $direction === 'rtl' ? 'ar' : 'en';
        app()->setLocale($locale);
        
        return $next($request);
    }
}
