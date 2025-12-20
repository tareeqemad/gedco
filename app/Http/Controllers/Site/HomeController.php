<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\ImpactStat;
use App\Models\Slider;
use App\Models\SiteConfig;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{
    public function index()
    {
        // Determine language from session direction
        $direction = session('direction', 'rtl');
        $language = $direction === 'rtl' ? 'ar' : 'en';

        // Cache key based on language
        $cacheKey = "home:{$language}";
        
        // Cache for 5 minutes (300 seconds)
        $data = Cache::remember($cacheKey, 300, function () use ($language) {
            // الآن يجب أن يكون هناك سجل واحد فقط
            $about = AboutUs::first();

            $sliders = Slider::where('is_active', true)
                ->language($language)
                ->orderBy('sort_order')
                ->get();
                
            $impactStats = ImpactStat::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
                
            $homeVideo = [
                'enabled' => (bool) SiteConfig::get('home_video_enabled', 0),
                'id'      => SiteConfig::get('home_video_id', ''),
                'caption' => SiteConfig::get('home_video_caption', 'شاهد فيديو تعريفي عن خدماتنا'),
            ];

            return compact('about', 'sliders', 'impactStats', 'homeVideo');
        });

        return view('site.home.index', $data);
    }
}
