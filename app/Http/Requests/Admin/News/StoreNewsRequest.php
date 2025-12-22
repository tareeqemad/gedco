<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('news.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'status'       => ['required', 'in:published,draft'],
            'featured'     => ['nullable', 'boolean'],
            'body'         => ['required', 'string'],
            'language'     => ['nullable', 'in:ar,en'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'pdf'          => ['nullable', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الخبر مطلوب.',
            'title.max' => 'عنوان الخبر يجب ألا يتجاوز 255 حرفاً.',
            'status.required' => 'حالة الخبر مطلوبة.',
            'status.in' => 'حالة الخبر غير صحيحة.',
            'body.required' => 'محتوى الخبر مطلوب.',
            'cover.image' => 'صورة الغلاف يجب أن تكون صورة.',
            'cover.mimes' => 'صورة الغلاف يجب أن تكون بصيغة: jpg, jpeg, png, webp.',
            'cover.max' => 'حجم صورة الغلاف يجب ألا يتجاوز 2MB.',
            'pdf.mimes' => 'الملف المرفق يجب أن يكون بصيغة PDF.',
            'pdf.max' => 'حجم الملف المرفق يجب ألا يتجاوز 10MB.',
        ];
    }
}

