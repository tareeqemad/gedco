<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $about = \App\Models\AboutUs::first();

        $sliders = \App\Models\Slider::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('site.home.index', compact('about', 'sliders'));
    }
}
