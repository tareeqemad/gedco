<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\StaffProfile;

class EnsureStaffEditSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // الـ id المسموح من السيشن (تم ضبطه بعد التحقق بكلمة المرور)
        $allowedId = (int) $request->session()->get('allowed_edit_profile_id', 0);

        // قيمة بارام المسار "profile" قد تكون Model أو id
        $routeParam = $request->route('profile');

        // استخرج الـid بأمان
        if ($routeParam instanceof StaffProfile) {
            $routeId = (int) $routeParam->getKey();
        } else {
            $routeId = (int) $routeParam; // في حال لم يحصل binding
        }

        // إن لم تتطابق الجلسة مع السجل المطلوب تعديله، رجّعه لصفحة التحقق
        if ($allowedId !== $routeId || $allowedId === 0) {
            return redirect()
                ->route('staff.profile.verify.form')
                ->withErrors(['auth' => 'انتهت جلسة التحقق أو لا تملك صلاحية تعديل هذا السجل.']);
        }

        return $next($request);
    }
}
