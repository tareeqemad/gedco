<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateDependentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // حاول نجيب الـ ID من الروت (عدّل الاسم حسب الراوت عندك)
        $staffId = $this->route('staff') ?? $this->route('id');

        return [
            'full_name'       => ['required','string','max:255'],

            'employee_number' => [
                'required','numeric','digits_between:1,4','max:1999',
                Rule::unique('staff_profiles','employee_number')->ignore($staffId),
            ],

            'national_id' => [
                'required','digits:9',
                Rule::unique('staff_profiles','national_id')->ignore($staffId),
            ],

            'mobile' => ['required','digits_between:8,10'],

            'location' => ['required', Rule::in(array_keys(config('staff_enums.locations')))],

            'job_title'        => ['nullable','string','max:100'],
            'department'       => ['nullable','string','max:100'],
            'directorate'      => ['nullable','string','max:100'],
            'section'          => ['nullable','string','max:100'],

            'marital_status'   => ['required', Rule::in(array_keys(config('staff_enums.marital_status')))],
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

            'family'               => ['required','array','max:10'],
            'family.*.name'        => ['nullable','string','max:255'],
            'family.*.relation'    => ['nullable', Rule::in(array_keys(config('staff_enums.relation')))],
            'family.*.birth_date'  => ['nullable','date'],
            'family.*.is_student'  => ['nullable', Rule::in(['yes','no'])],

            // في التحديث غالباً كلمة المرور اختيارية
            'password'              => ['nullable','string','min:6','confirmed'],
            'password_confirmation' => ['nullable','string','min:6'],
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

            $famCollection = collect($family);

            // عدد "الموظف نفسه"
            $selfCount = $famCollection->where('relation', 'self')->count();

            // عدد الأزواج والزوجات
            $husbands = $famCollection->where('relation', 'husband')->count();
            $wives    = $famCollection->where('relation', 'wife')->count();
            $spousesTotal = $husbands + $wives;

            // إجمالي أفراد الأسرة (يشمل الموظف)
            $count = $famCollection->count();

            /**
             * 1) الموظف لازم يكون موجود مرة واحدة فقط ضمن الأسرة
             */
            if ($selfCount === 0) {
                $validator->errors()->add('family', 'يجب إدخال الموظف نفسه ضمن أفراد الأسرة (علاقة: الموظف نفسه).');
            } elseif ($selfCount > 1) {
                $validator->errors()->add('family', 'يجب إدخال الموظف نفسه مرة واحدة فقط ضمن أفراد الأسرة.');
            }

            /**
             * 2) على الأقل فرد واحد في الأسرة (الموظف نفسه)
             */
            if ($count < 1) {
                $validator->errors()->add('family', 'يجب إدخال فرد أسرة واحد على الأقل (الموظف نفسه).');
            }

            /**
             * 3) منطق الحالة الاجتماعية مع الزوج/الزوجة
             */
            switch ($marital) {
                case 'single':   // أعزب/عزباء
                case 'widowed':  // أرمل/أرملة
                case 'divorced': // مطلق/مطلقة
                    if ($spousesTotal > 0) {
                        $validator->errors()->add(
                            'family',
                            'لا يمكن إدخال زوج أو زوجة مع الحالة الاجتماعية الحالية.'
                        );
                    }
                    break;

                case 'married': // متزوج/متزوجة
                    // لا يمكن أكثر من زوج واحد أو أكثر من زوجة واحدة
                    if ($husbands > 1 || $wives > 1) {
                        $validator->errors()->add(
                            'family',
                            'لا يمكن إدخال أكثر من زوج واحد أو أكثر من زوجة واحدة.'
                        );
                    }

                    // مجموع الأزواج/الزوجات لازم يكون واحد فقط (زوج أو زوجة)
                    if ($spousesTotal !== 1) {
                        $validator->errors()->add(
                            'family',
                            'يجب إدخال زوج واحد أو زوجة واحدة فقط للحالة: متزوج/متزوجة.'
                        );
                    }

                    // منطقياً: أقل شيء الموظف + زوج/زوجة
                    if ($count < 2) {
                        $validator->errors()->add(
                            'family',
                            'الحد الأدنى لأفراد الأسرة في حالة متزوج/متزوجة هو: الموظف + زوج/زوجة.'
                        );
                    }
                    break;
            }

            /**
             * 4) تكرار داخل نفس الطلب (اسم + تاريخ ميلاد)
             */
            $fam = $famCollection
                ->filter(fn($r) => filled($r['name'] ?? null) || filled($r['birth_date'] ?? null));

            $dups = $fam->groupBy(function ($r) {
                $name  = trim($r['name'] ?? '');
                $birth = $r['birth_date'] ?? '';
                return $name.'|'.$birth;
            })
                ->filter(fn($g) => $g->count() > 1);

            if ($dups->isNotEmpty()) {
                $validator->errors()->add('family', 'يوجد تكرار لأفراد أسرة (الاسم + تاريخ الميلاد).');
            }

            /**
             * 5) تحقق عمر الطالب الجامعي 17-30 + صحة تاريخ الميلاد
             */
            foreach ($family as $idx => $member) {
                $row      = $idx + 1;
                $birth    = $member['birth_date'] ?? null;
                $student  = $member['is_student'] ?? null;

                if (!empty($birth)) {
                    try {
                        $birthDate = Carbon::parse($birth);

                        if ($birthDate->isFuture()) {
                            $validator->errors()->add("family.$idx.birth_date", "تاريخ الميلاد في صف ($row) غير صالح (تاريخ مستقبلي).");
                            continue;
                        }

                        if ($student === 'yes') {
                            $age = $birthDate->age;
                            if ($age < 17 || $age > 30) {
                                $validator->errors()->add(
                                    "family.$idx.is_student",
                                    "عمر الطالب الجامعي يجب أن يكون بين 17 و 30 سنة (صف $row)."
                                );
                            }
                        }
                    } catch (\Exception $e) {
                        $validator->errors()->add("family.$idx.birth_date", "تاريخ الميلاد في صف ($row) غير صالح.");
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

            'employee_number.required'       => 'الرقم الوظيفي مطلوب.',
            'employee_number.numeric'        => 'الرقم الوظيفي يجب أن يكون رقميًا.',
            'employee_number.max'            => 'الرقم الوظيفي يجب أن يكون أقل من 2000.',
            'employee_number.digits_between' => 'الرقم الوظيفي يجب ألا يتجاوز 4 أرقام.',

            'national_id.required'  => 'رقم الهوية مطلوب.',
            'national_id.digits'    => 'رقم الهوية يجب أن يتكون من 9 أرقام.',

            'mobile.required'       => 'رقم الجوال مطلوب.',
            'mobile.digits_between' => 'رقم الجوال يجب ألا يتجاوز 10 أرقام.',

            'location.required'     => 'اختيار المقر مطلوب.',
            'location.in'           => 'المقر غير صحيح.',

            'marital_status.required' => 'الحالة الاجتماعية مطلوبة.',

            'housing_type.required'   => 'حالة السكن مطلوبة.',
            'current_address.required'=> 'العنوان الحالي بعد النزوح مطلوب.',

            'family.required'         => 'بيانات الأسرة مطلوبة.',
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

        // Clean family rows (إزالة الصفوف الفارغة)
        if (is_array($this->family)) {
            $clean = array_values(array_filter($this->family, function ($row) {
                return !empty($row['name'])
                    || !empty($row['relation'])
                    || !empty($row['birth_date'])
                    || !empty($row['is_student']);
            }));
            $this->merge(['family' => $clean]);
        }
    }
}
