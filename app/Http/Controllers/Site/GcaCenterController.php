<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class GcaCenterController extends Controller
{
    public function index()
    {
        return view('site.gca.index');
    }
}
