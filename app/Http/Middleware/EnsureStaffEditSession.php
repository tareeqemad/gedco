<?php

namespace App\Http\Middleware;

use App\Models\StaffProfile;
use Closure;
use Illuminate\Http\Request;

class EnsureStaffEditSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // ID المسموح له من جلسة التحقق
        $allowedId = (int) $request->session()->get('allowed_edit_profile_id', 0);

        // الحصول على الـ ID من الراوت (Model or numeric)
        $routeParam = $request->route('profile');

        if ($routeParam instanceof StaffProfile) {
            $routeId = (int) $routeParam->getKey();
        } else {
            $routeId = (int) $routeParam;
        }

        // 1) لا يوجد أي جلسة تحقق محفوظة → انتهت الجلسة أو ما عمل verify أصلاً
        if ($allowedId === 0) {
            return redirect()
                ->route('staff.profile.verify.form')
                ->withErrors([
                    'auth' => 'انتهت جلسة التحقق أو لم تقم بالتحقق بعد. يرجى إدخال رقم الهوية للمتابعة.',
                ]);
        }

        // 2) في جلسة تحقق، لكن الـ id في الرابط لا يطابق الـ id المسموح
        if ($allowedId !== $routeId) {
            // محاولة الوصول لملف ليس لك → صفحة منع صريحة
            return response()
                ->view('errors.403', [], 403);
            // أو: abort(403);
        }

        // 3) الحالة الطبيعية → مسموح له يدخل
        return $next($request);
    }
}
