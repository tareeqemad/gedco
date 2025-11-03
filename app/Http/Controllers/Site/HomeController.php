<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\ImpactStat;
use App\Models\Slider;
use App\Models\SiteConfig;


class HomeController extends Controller
{
    public function index()
    {
        $about =  AboutUs::first();

        $sliders =  Slider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $impactStats = ImpactStat::where('is_active', true)->orderBy('sort_order')->get();
        $homeVideo = [
            'enabled' => (bool) SiteConfig::get('home_video_enabled', 0),
            'id'      => SiteConfig::get('home_video_id', ''),
            'caption' => SiteConfig::get('home_video_caption', 'شاهد فيديو تعريفي عن خدماتنا'),
        ];

        return view('site.home.index', compact('about', 'sliders','impactStats'));


    }
}
