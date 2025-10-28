<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class TendersController extends Controller
{
    public function index()
    {
        return view('site.tenders.index');
    }
}
