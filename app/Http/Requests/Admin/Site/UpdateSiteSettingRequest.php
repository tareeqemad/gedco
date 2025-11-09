<?php

namespace App\Http\Requests\Admin\Site;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'footer_title_ar' => ['nullable','string','max:255'],
            'logo_white_path' => ['nullable','string','max:255'],

            // قنوات التواصل
            'channels' => ['nullable','array','max:2'],
            'channels.*.id'         => ['nullable','integer','exists:site_contact_channels,id'],
            'channels.*.position'   => ['nullable','integer','between:1,2'],
            'channels.*.label'      => ['nullable','string','max:50'],
            'channels.*.email'      => ['nullable','email','max:255'],
            'channels.*.phone'      => ['nullable','regex:/^\+?[0-9 ]{7,20}$/'],
            'channels.*.address_ar' => ['nullable','string','max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'channels.max' => 'مسموح إضافة قناتين تواصل كحد أقصى.',
            'channels.*.phone.regex' => 'صيغة الهاتف غير صحيحة.',
        ];
    }
}
