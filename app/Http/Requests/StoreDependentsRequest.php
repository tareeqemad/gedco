<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreDependentsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'full_name'       => ['required','string','max:255'],

            'employee_number' => [
                'required','numeric','digits_between:1,4','max:1999',
                Rule::unique('staff_profiles','employee_number'),
            ],

            'national_id' => [
                'required','digits:9',
                Rule::unique('staff_profiles','national_id'),
            ],

            'mobile' => ['required','digits_between:8,10'],

            'location' => ['required', Rule::in(array_keys(config('staff_enums.locations')))],

            'job_title'        => ['nullable','string','max:100'],
            'department'       => ['nullable','string','max:100'],
            'directorate'      => ['nullable','string','max:100'],
            'section'          => ['nullable','string','max:100'],
            'marital_status'   => ['nullable', Rule::in(array_keys(config('staff_enums.marital_status')))],
            'birth_date'       => ['nullable','date'],

            'house_status'     => ['nullable', Rule::in(array_keys(config('staff_enums.house_status')))],
            'status'           => ['nullable', Rule::in(array_keys(config('staff_enums.status')))],
            'current_address'  => ['nullable','string','max:255'],
            'housing_type'     => ['nullable', Rule::in(array_keys(config('staff_enums.housing_type')))],
            'original_address' => ['nullable','string','max:255'],

            'readiness'        => ['nullable', Rule::in(array_keys(config('staff_enums.readiness')))],
            'readiness_notes'  => ['nullable','string','max:1000'],

            'mobile_alt'       => ['nullable','digits_between:8,10'],
            'whatsapp'         => ['nullable','digits_between:8,10'],
            'telegram'         => ['nullable','string','max:50'],
            'gmail'            => ['nullable','email','max:150'],

            'has_family_incidents' => ['nullable', Rule::in(['yes','no'])],
            'family_notes'         => ['nullable','string','max:1000'],

            'family'               => ['nullable','array','max:10'],
            'family.*.name'        => ['nullable','string','max:255'],
            'family.*.relation'    => ['nullable', Rule::in(array_keys(config('staff_enums.relation')))],
            'family.*.birth_date'  => ['nullable','date'],
            'family.*.is_student'  => ['nullable', Rule::in(['yes','no'])],

            // كلمة المرور لإنشاء قدرة التعديل لاحقًا
            'password'              => ['required','string','min:6','confirmed'],
            'password_confirmation' => ['required','string','min:6'],
        ];
    }

    public function withValidator($validator)
    {
        // شروط إضافية
        $validator->sometimes('housing_type', ['required'], fn($i) =>
        in_array($i->status, ['resident','displaced'], true)
        );

        $validator->sometimes('current_address', ['required','string','max:255'], fn($i) =>
            $i->status === 'displaced'
        );

        $validator->sometimes('readiness_notes', ['required','string','max:1000'], fn($i) =>
            $i->readiness === 'not_ready'
        );

        $validator->sometimes('family_notes', ['required','string','max:1000'], fn($i) =>
            $i->has_family_incidents === 'yes'
        );

        $validator->after(function ($validator) {
            $input   = $this->all();
            $family  = $input['family'] ?? [];
            $marital = $input['marital_status'] ?? null;

            $count   = is_array($family) ? count($family) : 0;
            $spouses = collect($family)->where('relation', 'spouse')->count();

            switch ($marital) {
                case 'single':
                    if ($spouses > 0) $validator->errors()->add('family', 'لا يمكن إدخال زوج/زوجة مع الحالة: أعزب/عزباء.');
                    if ($count < 1)   $validator->errors()->add('family', 'يجب إدخال فرد أسرة واحد على الأقل للحالة: أعزب/عزباء.');
                    break;
                case 'married':
                    if ($spouses !== 1) $validator->errors()->add('family', 'يجب إدخال زوج/زوجة واحد فقط للحالة: متزوج/متزوجة.');
                    if ($count < 2)     $validator->errors()->add('family', 'الحد الأدنى 2 (زوج/زوجة + طفل/أخرى).');
                    break;
                case 'widowed':
                case 'divorced':
                    if ($spouses > 0) $validator->errors()->add('family', 'لا يمكن إدخال زوج/زوجة مع الحالة الحالية.');
                    if ($count < 1)   $validator->errors()->add('family', 'يجب إدخال فرد أسرة واحد على الأقل.');
                    break;
            }

            // تكرار داخل نفس الطلب (اسم + تاريخ ميلاد)
            $fam = collect($family)
                ->filter(fn($r) => filled($r['name'] ?? null) || filled($r['birth_date'] ?? null));
            $dups = $fam->groupBy(fn($r) => trim(($r['name'] ?? '').'|'.($r['birth_date'] ?? '')))
                ->filter(fn($g) => $g->count() > 1);
            if ($dups->isNotEmpty()) {
                $validator->errors()->add('family', 'يوجد تكرار لأفراد أسرة (الاسم + تاريخ الميلاد).');
            }

            // تحقق عمر الطالب الجامعي 17-30
            $today = now()->toDateString();
            foreach ($family as $idx => $member) {
                $row = $idx + 1;
                $birth = $member['birth_date'] ?? null;
                $student = $member['is_student'] ?? null;

                if (!empty($birth)) {
                    if (!strtotime($birth) || $birth > $today) {
                        $validator->errors()->add("family.$idx.birth_date", "تاريخ الميلاد في صف ($row) غير صالح.");
                    } elseif ($student === 'yes') {
                        $age = Carbon::parse($birth)->age;
                        if ($age < 17 || $age > 30) {
                            $validator->errors()->add("family.$idx.is_student", "عمر الطالب الجامعي يجب أن يكون بين 17 و 30 سنة (صف $row).");
                        }
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'employee_number.unique' => 'الرقم الوظيفي مستخدم مسبقًا.',
            'national_id.unique'     => 'رقم الهوية مستخدم مسبقًا.',
            'employee_number.required' => 'الرقم الوظيفي مطلوب.',
            'employee_number.numeric'  => 'الرقم الوظيفي يجب أن يكون رقميًا.',
            'employee_number.max'      => 'الرقم الوظيفي يجب أن يكون أقل من 2000.',
            'employee_number.digits_between' => 'الرقم الوظيفي يجب ألا يتجاوز 4 أرقام.',
            'national_id.required'  => 'رقم الهوية مطلوب.',
            'national_id.digits'    => 'رقم الهوية يجب أن يتكون من 9 أرقام.',
            'mobile.required'       => 'رقم الجوال مطلوب.',
            'mobile.digits_between' => 'رقم الجوال يجب ألا يتجاوز 10 أرقام.',
            'location.required'     => 'اختيار المقر مطلوب.',
            'location.in'           => 'المقر غير صحيح.',
            'housing_type.required' => 'حالة السكن مطلوبة.',
            'current_address.required' => 'العنوان الحالي بعد النزوح مطلوب.',
            'password.required'     => 'كلمة المرور مطلوبة.',
            'password.confirmed'    => 'تأكيد كلمة المرور غير مطابق.',
        ];
    }

    public function prepareForValidation(): void
    {
        // Normalize Arabic/English digits + trim
        $normalizeDigits = function ($value) {
            if (!is_string($value)) return $value;
            $eastern = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
            $western = ['0','1','2','3','4','5','6','7','8','9'];
            $value = str_replace($eastern, $western, $value);
            return trim($value);
        };

        $this->merge([
            'employee_number' => preg_replace('/\D/', '', $normalizeDigits($this->employee_number ?? '')),
            'national_id'     => preg_replace('/\D/', '', $normalizeDigits($this->national_id ?? '')),
            'mobile'          => preg_replace('/\D/', '', $normalizeDigits($this->mobile ?? '')),
            'mobile_alt'      => preg_replace('/\D/', '', $normalizeDigits($this->mobile_alt ?? '')),
            'whatsapp'        => preg_replace('/\D/', '', $normalizeDigits($this->whatsapp ?? '')),
        ]);

        // Clean family rows
        if (is_array($this->family)) {
            $clean = array_values(array_filter($this->family, function ($row) {
                return !empty($row['name']) || !empty($row['relation']) || !empty($row['birth_date']) || !empty($row['is_student']);
            }));
            $this->merge(['family' => $clean]);
        }
    }
}
