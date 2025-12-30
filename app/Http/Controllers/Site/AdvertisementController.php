<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $direction = session('direction', 'rtl');
        $isRtl = $direction === 'rtl';
        
        $advertisements = Advertisement::latest('DATE_NEWS')->paginate(6);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('site.advertisements.partials.grid', compact('advertisements', 'isRtl'))->render()
            ]);
        }

        return view('site.advertisements.index', compact('advertisements', 'isRtl'));
    }

    public function show($id)
    {
        $direction = session('direction', 'rtl');
        $isRtl = $direction === 'rtl';
        
        $ad = Advertisement::findOrFail($id);
        return view('site.advertisements.show', compact('ad', 'isRtl'));
    }
}
