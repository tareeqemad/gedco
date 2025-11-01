<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use App\Models\ImpactStat;
use App\Models\Slider;


class HomeController extends Controller
{
    public function index()
    {
        $about =  AboutUs::first();

        $sliders =  Slider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        $impactStats = ImpactStat::where('is_active', true)->orderBy('sort_order')->get();
        return view('site.home.index', compact('about', 'sliders','impactStats'));
    }
}
