<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        return view('admin.auth.login');
    }

    public function store(Request $request)
    {
        $cred = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'البريد الإلكتروني مطلوب.',
            'email.email'       => 'صيغة البريد غير صحيحة.',
            'password.required' => 'كلمة المرور مطلوبة.',
        ]);

        // لو عندك guard مخصص للإدمن:
        // $guard = Auth::guard('admin');
        // if ($guard->attempt($cred, $request->boolean('remember'))) { ... }

        if (Auth::attempt($cred, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // تحقق من كونه إدمن
            if (auth()->user() && auth()->user()->is_admin) {
                // استخدم intended
                return redirect()->intended(route('admin.dashboard'));
            }

            // لو مش إدمن، طلّعه برسالة عامة
            Auth::logout();
            return back()
                ->withErrors(['email' => 'بيانات الدخول غير صحيحة.']) // ما نكشف أنه “مش إدمن”
                ->onlyInput('email');
        }

        return back()
            ->withErrors(['email' => 'بيانات الدخول غير صحيحة.'])
            ->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        // لو guard إدمن:
        // Auth::guard('admin')->logout();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
