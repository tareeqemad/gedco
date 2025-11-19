<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffProfile;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class ProfileEditAuthController extends Controller
{
    public function showVerifyForm(Request $request): View
    {
        return view('staff.profile_dependents.verify', [
            'prefill_national_id' => $request->old('national_id', $request->query('national_id')),
        ]);
    }

    public function verify(Request $request)
    {
        // اليوزر ما بدخل إلا رقم الهوية
        $data = $request->validate([
            'national_id' => ['required', 'digits:9'],
        ]);

        // تنظيف الأرقام
        $nationalId = preg_replace('/\D/', '', $data['national_id'] ?? '');

        // البحث فقط برقم الهوية
        $profile = StaffProfile::query()
            ->where('national_id', (int)$nationalId)
            ->first();

        /**
         * لو ما في أي بروفايل بهذا الرقم:
         * -> روح مباشرة على صفحة الإدخال (إنشاء جديد)
         */
        if (!$profile) {
            return redirect()
                ->route('staff.profile.create')
                ->with('info', 'لا توجد بيانات سابقة لهذا رقم الهوية، يمكنك الآن تعبئة النموذج.');        }

        /**
         * لو موجود لكن خلّص محاولات التعديل
         */
        if (!$profile->canEdit()) {
            return back()
                ->withErrors(['national_id' => 'انتهت محاولات التعديل المسموح بها.'])
                ->withInput();
        }

        /**
         * كلمة المرور المخزنة = رقم الهوية
         * نتحقق داخلياً بدون ما نطلب كلمة مرور من المستخدم
         */
        if (!$profile->verifyPassword((string)$nationalId)) {
            return back()
                ->withErrors(['national_id' => 'تعذر التحقق من الهوية، يرجى مراجعة شؤون الموظفين.'])
                ->withInput();
        }

        // سماح التعديل في الـ session
        $request->session()->put('allowed_edit_profile_id', $profile->getKey());
        $request->session()->put('allowed_edit_expires_at', now()->addMinutes(30)->toISOString());

        // تجديد session id
        $request->session()->migrate(true);

        return redirect()
            ->route('staff.profile.edit', ['profile' => $profile->getKey()])
            ->with('info', 'تم التحقق بنجاح. يمكنك تعديل بياناتك الآن.');
    }
}
