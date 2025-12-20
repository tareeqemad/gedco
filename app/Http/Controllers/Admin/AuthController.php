<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create()
    {
        $direction = session('direction', 'rtl');
        return view('admin.auth.login', compact('direction'));
    }

    public function store(Request $request)
    {
        $direction = session('direction', 'rtl');
        $locale = $direction === 'rtl' ? 'ar' : 'en';
        \App::setLocale($locale);

        $cred = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ], [
            'email.required'    => __('admin.auth.email_required'),
            'email.email'       => __('admin.auth.email_invalid'),
            'password.required' => __('admin.auth.password_required'),
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
                ->withErrors(['email' => __('admin.auth.invalid_credentials')])
                ->onlyInput('email');
        }

        return back()
            ->withErrors(['email' => __('admin.auth.invalid_credentials')])
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
