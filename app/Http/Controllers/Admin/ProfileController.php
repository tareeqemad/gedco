<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $breadcrumbTitle = 'ملفي الشخصي';
        return view('admin.profile.edit', compact('user', 'breadcrumbTitle'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->only('name', 'email');

        if ($request->hasFile('avatar')) {
            // 1. امسح القديم
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // 2. ارفع الجديد
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path; // ← هيحفظ في الداتابيز
        }

        $user->update($data);

        return back()->with('success', 'تم تحديث البيانات بنجاح!');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        $request->session()->regenerate();
        return back()->with('success_password', 'تم تغيير كلمة المرور بنجاح.');
    }
}
