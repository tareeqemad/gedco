<?php

namespace App\Http\Requests\Admin\Site;

use Illuminate\Foundation\Http\FormRequest;

class StoreFooterLinkRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'group'      => ['required','in:services,company'],
            'label_ar'   => ['required','string','max:255'],
            'route_name' => ['nullable','string','max:255'],
            'url'        => ['nullable','url','max:255'],
            'sort_order' => ['nullable','integer','min:0'],
            'is_active'  => ['nullable','boolean'],
        ];
    }
}
class UpdateFooterLinkRequest extends StoreFooterLinkRequest {}
