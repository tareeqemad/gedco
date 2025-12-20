<?php

namespace App\Http\Requests\Admin\Site;

use Illuminate\Foundation\Http\FormRequest;

class WhyChooseUsRequest extends FormRequest
{
    /**
     * تحديد من يُسمح له بتنفيذ هذا الطلب.
     */
    public function authorize(): bool
    {
        $user = $this->user(); // المستخدم الحالي

        if (!$user) return false; // لو غير مسجل دخول

        // 👇 نتحقق من الصلاحية حسب نوع الطلب
        if ($this->isMethod('POST')) {
            // إنشاء سجل جديد
            return $user->can('why.create');
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // تعديل سجل موجود
            return $user->can('why.edit');
        }

        if ($this->isMethod('DELETE')) {
            // حذف سجل
            return $user->can('why.delete');
        }

        // بشكل افتراضي: فقط عرض الصفحة
        return $user->can('why.view');
    }

    /**
     * قواعد التحقق
     */
    public function rules(): array
    {
        return [
            // معلومات القسم العامة - العربية
            'badge'       => ['required', 'string', 'max:100'],
            'tagline'     => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            // معلومات القسم العامة - الإنجليزية
            'badge_en'       => ['nullable', 'string', 'max:100'],
            'tagline_en'     => ['nullable', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],

            // عناصر الميزات (features) - العربية
            'feature_title'   => ['sometimes', 'array'],
            'feature_title.*' => ['nullable', 'string', 'max:255'],

            'feature_text'    => ['sometimes', 'array'],
            'feature_text.*'  => ['nullable', 'string'],

            // عناصر الميزات (features) - الإنجليزية
            'feature_title_en'   => ['sometimes', 'array'],
            'feature_title_en.*' => ['nullable', 'string', 'max:255'],

            'feature_text_en'    => ['sometimes', 'array'],
            'feature_text_en.*'  => ['nullable', 'string'],

            'feature_icon'    => ['sometimes', 'array'],
            'feature_icon.*'  => ['nullable', 'string', 'max:80'],

            // حالة التفعيل
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * رسائل التحقق
     */
    public function messages(): array
    {
        return [
            'badge.required'   => 'نص الشارة مطلوب.',
            'tagline.required' => 'العنوان الرئيسي مطلوب.',
        ];
    }

    /**
     * فلترة القيم قبل التحقق
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
