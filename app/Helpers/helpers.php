<?php

if (!function_exists('build_image_url')) {
    /**
     * Builds a full URL for an image path, handling different path formats.
     * Handles external URLs, assets folder, storage folder, and regular storage paths.
     *
     * @param string|null $path The image path
     * @param string|null $fallback Fallback image path if $path is empty
     * @return string Full URL to the image
     */
    function build_image_url(?string $path, ?string $fallback = null): string
    {
        // Return fallback if path is empty
        if (blank($path)) {
            return $fallback ? asset($fallback) : asset('assets/admin/images/placeholder.png');
        }

        // External URLs (http/https)
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Assets folder paths
        if (str_starts_with($path, 'assets/') || str_starts_with($path, 'public/')) {
            return asset($path);
        }

        // Storage folder paths (with storage/ prefix)
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        // Regular storage paths (assumed to be in storage/app/public)
        return asset('storage/' . ltrim($path, '/'));
    }
}

if (!function_exists('get_image_url')) {
    /**
     * Alias for build_image_url for backward compatibility.
     *
     * @param string|null $path The image path
     * @param string|null $fallback Fallback image path if $path is empty
     * @return string Full URL to the image
     */
    function get_image_url(?string $path, ?string $fallback = null): string
    {
        return build_image_url($path, $fallback);
    }
}

if (!function_exists('get_relative_path')) {
    /**
     * Extracts relative path from a full URL, returning only the path and query string.
     *
     * @param string|null $url Full URL or null
     * @param string|null $fallback Fallback value if URL is null
     * @return string|null Relative path with query string or fallback
     */
    function get_relative_path(?string $url, ?string $fallback = null): ?string
    {
        if (!$url) {
            return $fallback;
        }

        $parts = parse_url($url);
        $path = $parts['path'] ?? '/';
        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';

        return $path . $query;
    }
}

if (!function_exists('clear_home_cache')) {
    /**
     * Clears the home page cache for both Arabic and English languages.
     * This function should be called whenever content that appears on the home page is updated,
     * such as sliders, about us, impact stats, or site settings.
     *
     * @return void
     */
    function clear_home_cache(): void
    {
        \Illuminate\Support\Facades\Cache::forget('home:ar');
        \Illuminate\Support\Facades\Cache::forget('home:en');
    }
}

