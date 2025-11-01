<?php
namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'current_password'      => ['required','current_password'],
            'password'              => ['required','string','min:8','confirmed'],
            'password_confirmation' => ['required','string','min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'كلمة المرور الحالية مطلوبة.',
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
            'password.required' => 'كلمة المرور الجديدة مطلوبة.',
            'password.min'      => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.confirmed'=> 'تأكيد كلمة المرور غير متطابق.',
        ];
    }
}
