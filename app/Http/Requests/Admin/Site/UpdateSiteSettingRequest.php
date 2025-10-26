<?php

namespace App\Http\Requests\Admin\Site;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'footer_title_ar' => ['required','string','max:255'],
            'logo_white_path' => ['nullable','string','max:255'],

            'contact_email'   => ['nullable','email','max:255'],
            'contact_phone'   => ['nullable','string','max:255'],
            'contact_address' => ['nullable','string','max:255'],
        ];
    }
}
