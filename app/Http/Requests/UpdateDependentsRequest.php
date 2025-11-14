<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateDependentsRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $profile = $this->route('profile'); // Model bound

        return [
            'full_name'       => ['required','string','max:255'],

            'employee_number' => [
                'required','numeric','digits_between:1,4','max:1999',
                Rule::unique('staff_profiles','employee_number')->ignore($profile?->id),
            ],

            'national_id' => [
                'required','digits:9',
                Rule::unique('staff_profiles','national_id')->ignore($profile?->id),
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

            // كلمة المرور: اختيارية في التعديل
            'password'              => ['nullable','string','min:6','confirmed'],
            'password_confirmation' => ['nullable','string','min:6'],
        ];
    }

    public function withValidator($validator)
    {
        // نفس شروط Store (نسخ لصق مبسطًا)
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

            // منع تكرار داخل نفس الطلب
            $fam = collect($family)
                ->filter(fn($r) => filled($r['name'] ?? null) || filled($r['birth_date'] ?? null));
            $dups = $fam->groupBy(fn($r) => trim(($r['name'] ?? '').'|'.($r['birth_date'] ?? '')))
                ->filter(fn($g) => $g->count() > 1);
            if ($dups->isNotEmpty()) {
                $validator->errors()->add('family', 'يوجد تكرار لأفراد أسرة (الاسم + تاريخ الميلاد).');
            }

            // عمر الطالب الجامعي
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
        return (new StoreDependentsRequest)->messages(); // نفس الرسائل
    }

    public function prepareForValidation(): void
    {
        (new StoreDependentsRequest)->prepareForValidation(); // نفس التنظيف
    }
}
