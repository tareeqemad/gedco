<?php

namespace App\Http\Requests\Admin\Site;

use Illuminate\Foundation\Http\FormRequest;

class AboutUsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }


        if ($this->routeIs('admin.site.about.store') || $this->routeIs('admin.about.create') || $this->isMethod('post')) {
            return $user->can('about.create');
        }

        if ($this->routeIs('admin.site.about.update') || $this->routeIs('admin.site.about.edit') || $this->isMethod('put') || $this->isMethod('patch')) {
            return $user->can('about.edit');
        }

        return false;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'title'         => ['required', 'string', 'max:255'],
            'subtitle'      => ['nullable', 'string', 'max:255'],
            'paragraph1'    => ['required', 'string'],
            'paragraph2'    => ['nullable', 'string'],
            'features_col1' => ['nullable', 'string'],
            'features_col2' => ['nullable', 'string'],
            'image'         => [$isUpdate ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'العنوان مطلوب.',
            'paragraph1.required'  => 'الفقرة الأولى مطلوبة.',
            'image.required'       => 'الصورة مطلوبة لأول مرة.',
            'image.image'          => 'الملف يجب أن يكون صورة.',
            'image.mimes'          => 'الامتداد يجب أن يكون jpg أو jpeg أو png أو webp.',
            'image.max'            => 'أقصى حجم مسموح هو 2 ميجابايت.',
        ];
    }
}
