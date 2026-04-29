<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDirectory;
use App\Models\StaffProfile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProfileEditAuthController extends Controller
{
    /**
     * عرض فورم إدخال رقم الهوية + الرقم الوظيفي قبل التعديل / التسجيل
     */
    public function showVerifyForm(Request $request): View
    {
        return view('staff.profile_dependents.verify', [
            'prefill_national_id'     => old('national_id', $request->query('id')),
            'prefill_employee_number' => old('employee_number', $request->query('no')),
        ]);
    }

    /**
     * منطق التحقق:
     *  - لو الهوية + الرقم الوظيفي موجودين في جدول employee_directory وغير موجودة في DB -> تحويل لصفحة create
     *  - لو غير موجودين في employee_directory -> رسالة خطأ
     *  - لو موجودين في employee_directory + في DB -> فتح صفحة edit
     */
    public function verify(Request $request)
    {
        // تنظيف + تحقق
        $request->merge([
            'national_id'     => preg_replace('/\D/', '', (string) $request->input('national_id')),
            'employee_number' => preg_replace('/\D/', '', (string) $request->input('employee_number')),
        ]);

        $data = $request->validate([
            'national_id'     => ['required', 'digits:9'],
            'employee_number' => ['required', 'numeric', 'min:1', 'max:9999'],
        ], [
            'national_id.required'     => 'رقم الهوية مطلوب.',
            'national_id.digits'       => 'رقم الهوية يجب أن يكون 9 أرقام.',
            'employee_number.required' => 'الرقم الوظيفي مطلوب.',
            'employee_number.numeric'  => 'الرقم الوظيفي يجب أن يكون رقمًا.',
        ]);

        $nationalId     = $data['national_id'];
        $employeeNumber = (int) $data['employee_number'];

        // 1) نتأكد أولاً إن الموظف موجود في جدول الدليل
        $directoryEntry = EmployeeDirectory::where('national_id', $nationalId)
            ->where('employee_number', $employeeNumber)
            ->first();

        if (! $directoryEntry) {
            return back()
                ->withErrors(['national_id' => 'رقم الهوية أو الرقم الوظيفي غير صحيح أو غير موجود في النظام.'])
                ->withInput();
        }

        // 2) هل يوجد ملف للموظف في قاعدة البيانات؟
        $profile = StaffProfile::where('national_id', $nationalId)->first();

        // حالة: موجود في الدليل وغير موجود في DB -> نوديه على create
        if (! $profile) {
            session([
                'verified_national_id'      => $nationalId,
                'verified_employee_number'  => $employeeNumber,
                'verified_full_name'        => $directoryEntry->full_name,
                'verified_job_title'        => $directoryEntry->job_title,
                'verified_mobile'           => $directoryEntry->mobile,
                'verified_email'            => $directoryEntry->email,
                'verified_spouse_name'      => $directoryEntry->spouse_name,
                'verified_spouse_national_id' => $directoryEntry->spouse_national_id,
                'verified_family_count'     => $directoryEntry->family_members_count,
                'verified_governorate'      => $directoryEntry->governorate,
            ]);

            return redirect()
                ->route('staff.profile.create')
                ->with('info', 'تم التحقق من رقم الهوية، الرجاء استكمال تسجيل البيانات.');
        }

        // حالة: موجود في DB + موجود في الدليل -> نسمح له بالتعديل
        session([
            'allowed_edit_profile_id' => $profile->id,
        ]);

        return redirect()
            ->route('staff.profile.edit', $profile->id)
            ->with('info', 'تم التحقق من رقم الهوية، يمكنك الآن تعديل بياناتك.');
    }
}
