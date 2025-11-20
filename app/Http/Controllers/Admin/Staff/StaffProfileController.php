<?php

namespace App\Http\Controllers\Admin\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use Illuminate\Http\Request;

class StaffProfileController extends Controller
{


    public function index(Request $request)
    {
        $query = StaffProfile::query();


        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('national_id', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }


        $profiles = $query->latest()->paginate(25);

        return view('admin.staff_profiles.index', compact('profiles', 'search'));
    }


    public function show(StaffProfile $profile)
    {
        return view('admin.staff_profiles.show', compact('profile'));
    }
}
