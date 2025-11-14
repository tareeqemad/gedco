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
            // تعبئة مسبقة لراحة المستخدم
            'prefill_by'    => $request->old('by', $request->query('by', 'national_id')), // national_id | employee_number
            'prefill_value' => $request->old('value', $request->query('value')),
        ]);
    }

    public function verify(Request $request)
    {
        $data = $request->validate([
            'by'       => ['required', 'in:national_id,employee_number'],
            'value'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);


        $value = preg_replace('/\D/', '', $data['value'] ?? '');

        // ابحث حسب المفتاح المختار
        $profile = StaffProfile::query()
            ->when($data['by'] === 'national_id',     fn($q) => $q->where('national_id',     (int) $value))
            ->when($data['by'] === 'employee_number', fn($q) => $q->where('employee_number', (int) $value))
            ->first();

        if (!$profile) {
            return back()
                ->withErrors(['value' => 'لم يتم العثور على بيانات مطابقة.'])
                ->withInput();
        }

        if (!$profile->canEdit()) {
            return back()
                ->withErrors(['value' => 'انتهت محاولات التعديل المسموح بها.'])
                ->withInput();
        }

        if (!$profile->verifyPassword($data['password'])) {
            return back()
                ->withErrors(['password' => 'كلمة المرور غير صحيحة.'])
                ->withInput();
        }


        $request->session()->put('allowed_edit_profile_id', $profile->getKey());

        $request->session()->put('allowed_edit_expires_at', now()->addMinutes(30)->toISOString());


        $request->session()->migrate(true);


        return redirect()
            ->route('staff.profile.edit', ['profile' => $profile->getKey()])
            ->with('info', 'تم التحقق بنجاح. يمكنك تعديل بياناتك الآن.');
    }
}
