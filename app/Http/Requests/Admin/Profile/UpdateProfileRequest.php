<?php
namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $id = $this->user()->id;
        return [
            'name'   => ['required','string','max:255'],
            'email'  => ['required','email','max:255', Rule::unique('users','email')->ignore($id)],
            'avatar' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'الاسم مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email'    => 'صيغة البريد غير صحيحة.',
            'email.unique'   => 'هذا البريد مستخدم بالفعل.',
            'avatar.image'   => 'الملف يجب أن يكون صورة.',
        ];
    }
}
