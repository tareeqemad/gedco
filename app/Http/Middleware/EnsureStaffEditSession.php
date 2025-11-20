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
         $allowedId = (int) $request->session()->get('allowed_edit_profile_id', 0);

         $routeParam = $request->route('profile');

         if ($routeParam instanceof StaffProfile) {
            $routeId = (int) $routeParam->getKey();
        } else {
            $routeId = (int) $routeParam;
        }


        if ($allowedId !== $routeId || $allowedId === 0) {
            return redirect()
                ->route('staff.profile.verify.form')
                ->withErrors([
                    'auth' => 'انتهت جلسة التحقق أو لا تملك صلاحية تعديل هذا السجل.',
                ]);
        }

        return $next($request);
    }
}
