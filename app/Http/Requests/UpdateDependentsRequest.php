<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Carbon\Carbon;
use App\Models\StaffProfile;

/**
 * طلب تعديل ملف الموظف + أفراد الأسرة.
 *
 * نفس منطق StoreDependentsRequest مع مراعاة تجاهل السجل الحالي في قيود unique.
 */
class UpdateDependentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var StaffProfile|null $profile */
        $profile = $this->route('profile');
        $profileId = $profile instanceof StaffProfile ? $profile->getKey() : $profile;

        return [
            // البيانات الأساسية
            'full_name'       => ['required','string','max:255'],

            'employee_number' => [
                'required','numeric','digits_between:1,4','max:1999',
                Rule::unique('staff_profiles','employee_number')->ignore($profileId),
            ],

            'national_id'     => [
                'required','numeric','digits:9',
                Rule::unique('staff_profiles','national_id')->ignore($profileId),
            ],

            'mobile'          => ['required','digits_between:8,10'],

            'location'        => ['required', Rule::in(array_keys(config('staff_enums.locations')))],

            'job_title'       => ['nullable','string','max:100'],
            'department'      => ['nullable','string','max:100'],
            'directorate'     => ['nullable','string','max:100'],
            'section'         => ['nullable','string','max:100'],

            'marital_status'  => ['required', Rule::in(array_keys(config('staff_enums.marital_status')))],
            'birth_date'      => ['nullable','date'],

            // السكن
            'house_status'     => ['nullable', Rule::in(array_keys(config('staff_enums.house_status')))],
            'status'           => ['nullable', Rule::in(array_keys(config('staff_enums.status')))],
            'current_address'  => ['nullable','string','max:255'],
            'housing_type'     => ['nullable', Rule::in(array_keys(config('staff_enums.housing_type')))],
            'original_address' => ['nullable','string','max:255'],

            // الجاهزية
            'readiness'       => ['nullable', Rule::in(array_keys(config('staff_enums.readiness')))],
            'readiness_notes' => ['nullable','string','max:1000'],

            // وسائل التواصل الإضافية
            'mobile_alt'      => ['nullable','digits_between:8,10'],
            'whatsapp'        => ['nullable','digits_between:8,10'],
            'telegram'        => ['nullable','string','max:50'],
            'gmail'           => ['nullable','email','max:150'],

            // أحداث الأسرة
            'has_family_incidents' => ['nullable', Rule::in(['yes','no'])],
            'family_notes'         => ['nullable','string','max:1000'],

            // بيانات الأسرة
            'family_members_count' => ['nullable','integer','min:1','max:10'],

            'family'               => ['nullable','array','max:10'],
            'family.*.name'        => ['nullable','string','max:255'],
            'family.*.relation'    => ['nullable', Rule::in(array_keys(config('staff_enums.relation')))],
            'family.*.birth_date'  => ['nullable','date'],
            'family.*.is_student'  => ['nullable', Rule::in(['yes','no'])],
        ];
    }

    public function withValidator($validator): void
    {
        // شروط إضافية بحسب القيم
        $validator->sometimes('housing_type', ['required'], function ($input) {
            return in_array($input->status, ['resident','displaced'], true);
        });

        $validator->sometimes('current_address', ['required','string','max:255'], function ($input) {
            return $input->status === 'displaced';
        });

        $validator->sometimes('readiness_notes', ['required','string','max:1000'], function ($input) {
            return $input->readiness === 'not_ready';
        });

        $validator->sometimes('family_notes', ['required','string','max:1000'], function ($input) {
            return $input->has_family_incidents === 'yes';
        });

        /** @var Validator $validator */
        $validator->after(function (Validator $validator) {
            $input   = $this->all();
            $family  = $input['family'] ?? [];
            $marital = $input['marital_status'] ?? null;

            if (!is_array($family)) {
                return;
            }

            $fam = collect($family)->filter(function ($row) {
                return !empty($row['name'])
                    || !empty($row['relation'])
                    || !empty($row['birth_date'])
                    || !empty($row['is_student']);
            });

            // 1) التحقق من عدد الأزواج / الزوجات
            $spousesTotal = $fam->whereIn('relation', ['husband','wife'])->count();

            if ($marital === 'married') {
                if ($spousesTotal === 0) {
                    $validator->errors()->add('family', 'يجب إدخال زوج أو زوجة واحد على الأقل للحالة متزوج/متزوجة.');
                }
            } else {
                if ($spousesTotal > 0) {
                    $validator->errors()->add('family', 'لا يمكن إدخال زوج/زوجة في أفراد الأسرة ما دمت لست متزوجًا في الحالة الاجتماعية.');
                }
            }

            // 2) التحقق من عدد أفراد الأسرة مقابل الحقل العددي (إن وُجد)
            $countField = (int) ($input['family_members_count'] ?? 0);
            if ($countField > 0 && $fam->count() !== $countField) {
                $validator->errors()->add('family_members_count', 'عدد أفراد الأسرة في الجدول لا يطابق الرقم المدخل في الحقل.');
            }

            // 3) منع التكرار (الاسم + تاريخ الميلاد)
            $dups = $fam->groupBy(function ($row) {
                $name  = trim($row['name'] ?? '');
                $birth = $row['birth_date'] ?? '';
                return $name.'|'.$birth;
            })->filter(function ($group) {
                return $group->count() > 1;
            });

            if ($dups->isNotEmpty()) {
                $validator->errors()->add('family', 'يوجد تكرار لأفراد أسرة (نفس الاسم وتاريخ الميلاد).');
            }

            // 4) التحقق من صحة تاريخ الميلاد وعمر الطلاب الجامعيين
            foreach ($fam as $idx => $member) {
                $row      = $idx + 1;
                $birth    = $member['birth_date'] ?? null;
                $student  = $member['is_student'] ?? null;

                if (empty($birth)) {
                    continue;
                }

                try {
                    $birthDate = Carbon::parse($birth);
                } catch (\Throwable $e) {
                    $validator->errors()->add("family.$idx.birth_date", "تاريخ الميلاد في صف ($row) غير صالح.");
                    continue;
                }

                if ($birthDate->isFuture()) {
                    $validator->errors()->add("family.$idx.birth_date", "تاريخ الميلاد في صف ($row) غير صالح (تاريخ مستقبلي).");
                    continue;
                }

                if ($student === 'yes') {
                    $age = $birthDate->age;
                    if ($age < 17 || $age > 30) {
                        $validator->errors()->add(
                            "family.$idx.is_student",
                            "عمر الطالب الجامعي في صف ($row) يجب أن يكون بين 17 و 30 سنة."
                        );
                    }
                }
            }
        });
    }

    protected function prepareForValidation(): void
    {
        // دالة بسيطة لتحويل الأرقام العربية/الفارسية إلى إنجليزية ثم تنظيف المدخل
        $normalizeDigits = function ($value): string {
            $value = (string) $value;

            $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
            $latin  = ['0','1','2','3','4','5','6','7','8','9'];
            $value  = str_replace($arabic, $latin, $value);

            $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
            $value   = str_replace($persian, $latin, $value);

            return trim($value);
        };

        $this->merge([
            'employee_number' => preg_replace('/\D/', '', $normalizeDigits($this->employee_number ?? '')),
            'national_id'     => preg_replace('/\D/', '', $normalizeDigits($this->national_id ?? '')),
            'mobile'          => preg_replace('/\D/', '', $normalizeDigits($this->mobile ?? '')),
            'mobile_alt'      => preg_replace('/\D/', '', $normalizeDigits($this->mobile_alt ?? '')),
            'whatsapp'        => preg_replace('/\D/', '', $normalizeDigits($this->whatsapp ?? '')),
        ]);

        // تنظيف صفوف الأسرة من الصفوف الفارغة بالكامل
        if (is_array($this->family)) {
            $clean = [];
            foreach ($this->family as $row) {
                if (!empty($row['name'])
                    || !empty($row['relation'])
                    || !empty($row['birth_date'])
                    || !empty($row['is_student'])) {
                    $clean[] = $row;
                }
            }
            $this->merge(['family' => array_values($clean)]);
        }
    }
}
