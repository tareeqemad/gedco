<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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

        if (Auth::attempt($cred, $request->boolean('remember'))) {
            // حذف المحاولات الخاطئة عند نجاح تسجيل الدخول
            $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
            RateLimiter::clear($throttleKey);

            // إعادة توليد الـ session ID للأمان (مهم جداً للأمان)
            $request->session()->regenerate();

            // حفظ معلومات تسجيل الدخول في الـ session للمراقبة
            session()->put('login_time', now());
            session()->put('login_ip', $request->ip());
            session()->put('user_agent', $request->userAgent());
            session()->put('last_activity', now());

            // تحقق من كونه إدمن
            if (auth()->user() && auth()->user()->is_admin) {
                // استخدم intended للعودة للصفحة المطلوبة
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', __('admin.auth.login_success'));
            }

            // لو مش إدمن، طلّعه برسالة عامة
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return back()
                ->withErrors(['email' => __('admin.auth.invalid_credentials')])
                ->onlyInput('email');
        }

        // حساب المحاولات المتبقية (Rate Limiter middleware يتعامل مع الحد)
        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
        $attempts = RateLimiter::attempts($throttleKey);
        $remaining = max(0, 5 - $attempts);

        throw ValidationException::withMessages([
            'email' => $remaining > 0 
                ? __('admin.auth.invalid_credentials') . ' (' . $remaining . ' ' . __('admin.auth.attempts_remaining') . ')'
                : __('admin.auth.invalid_credentials'),
        ]);
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
