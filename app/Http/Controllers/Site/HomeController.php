<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Cache::remember('home:sliders', 3600, function () {
            return Slider::where('is_active', true)->orderBy('sort_order')->get();
        });

        return view('site.home.index', compact('sliders'));
    }
}
