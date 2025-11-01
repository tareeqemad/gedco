<?php

namespace App\Http\Requests\Admin\Site;

use Illuminate\Foundation\Http\FormRequest;

class ImpactStatRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array {
        return [
            'title_ar'   => 'required|string|max:255',
            'amount_usd' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }
}
