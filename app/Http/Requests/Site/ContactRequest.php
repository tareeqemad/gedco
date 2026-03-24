<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم يجب أن يكون أقل من 255 حرف',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'subject.required' => 'الموضوع مطلوب',
            'subject.max' => 'الموضوع يجب أن يكون أقل من 255 حرف',
            'message.required' => 'الرسالة مطلوبة',
            'message.min' => 'الرسالة يجب أن تكون على الأقل 10 أحرف',
            'message.max' => 'الرسالة يجب أن تكون أقل من 2000 حرف',
            'phone.regex' => 'رقم الهاتف غير صحيح',
        ];
    }
}
