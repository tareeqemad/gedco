<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $advertisements = Advertisement::latest('DATE_NEWS')->paginate(9);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('site.advertisements.partials.grid', compact('advertisements'))->render()
            ]);
        }

        return view('site.advertisements.index', compact('advertisements'));
    }

    public function show($id)
    {
        $ad = Advertisement::findOrFail($id);
        return view('site.advertisements.show', compact('ad'));
    }
}
