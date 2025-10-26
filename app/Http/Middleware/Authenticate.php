<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * تحديد المسار الذي يجب إعادة التوجيه إليه عند عدم تسجيل الدخول.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // ✅ هذا هو السحر الحقيقي
        if ($request->is('admin*')) {
            return route('admin.login');
        }

        // في حال أضفت لاحقاً صفحة login عادية
        return route('login');
    }
}
