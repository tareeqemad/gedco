<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobsController extends Controller
{
    /**
     * عرض صفحة الشهادات.
     */
    public function index()
    {
        $jobs = Job::where('is_active', true)
            ->orderBy('sort')
            ->orderByDesc('id') // احتياطي لو نفس sort
            ->get();

        return view('site.jobs.index', compact('jobs'));
    }
}
