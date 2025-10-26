<?php

namespace App\Http\Requests\Admin\Site;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialLinkRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'platform'   => ['required','in:facebook,x,instagram,youtube,whatsapp'],
            'icon_class' => ['required','string','max:255'], // fa-brands fa-...
            'url'        => ['required','url','max:255'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active'  => ['nullable','boolean'],
        ];
    }
}
class UpdateSocialLinkRequest extends StoreSocialLinkRequest {}
