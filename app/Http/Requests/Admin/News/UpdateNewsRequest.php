<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('news.edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'min:5', 'max:200'],
            'published_at' => ['required', 'date'],
            'status'       => ['required', 'in:draft,published'],
            'featured'     => ['nullable', 'boolean'],
            'body'         => ['required', 'string'],
            'language'     => ['nullable', 'in:ar,en'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=300,min_height=200'],
            'pdf'          => ['nullable', 'mimetypes:application/pdf', 'max:10240'],
            'remove_cover' => ['nullable', 'boolean'],
            'remove_pdf'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الخبر مطلوب.',
            'title.min' => 'عنوان الخبر يجب أن يكون على الأقل 5 أحرف.',
            'title.max' => 'عنوان الخبر يجب ألا يتجاوز 200 حرفاً.',
            'published_at.required' => 'تاريخ النشر مطلوب.',
            'published_at.date' => 'تاريخ النشر غير صحيح.',
            'status.required' => 'حالة الخبر مطلوبة.',
            'status.in' => 'حالة الخبر غير صحيحة.',
            'body.required' => 'محتوى الخبر مطلوب.',
            'cover.image' => 'صورة الغلاف يجب أن تكون صورة.',
            'cover.mimes' => 'صورة الغلاف يجب أن تكون بصيغة: jpg, jpeg, png, webp.',
            'cover.max' => 'حجم صورة الغلاف يجب ألا يتجاوز 2MB.',
            'cover.dimensions' => 'صورة الغلاف يجب أن تكون على الأقل 300×200 بكسل.',
            'pdf.mimetypes' => 'الملف المرفق يجب أن يكون بصيغة PDF.',
            'pdf.max' => 'حجم الملف المرفق يجب ألا يتجاوز 10MB.',
        ];
    }
}

