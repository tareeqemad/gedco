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
        // breadcrumbs اختيارياً
        $breadcrumbTitle = 'ملفي الشخصي';
        return view('admin.profile.edit', compact('user','breadcrumbTitle'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->only('name','email');

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            // امسح القديم لو محفوظ داخل storage/public
            if ($user->avatar
                && !str_starts_with($user->avatar, 'http')
                && !str_starts_with($user->avatar, 'assets/')
                && !str_starts_with($user->avatar, 'public/')
                && !str_starts_with($user->avatar, 'storage/')
                && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }

            // خزّن في public disk => يرجع مسار مثل: avatars/xxxx.webp
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success','تم تحديث البيانات.');
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        // أعد توليد session id لأمان إضافي
        $request->session()->regenerate();

        return back()->with('success_password','تم تغيير كلمة المرور بنجاح.');
    }
}
