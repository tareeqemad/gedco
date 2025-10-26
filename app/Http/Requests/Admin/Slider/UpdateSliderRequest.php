<?php

namespace App\Http\Requests\Admin\Slider;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()?->can('sliders.edit') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['nullable','string','max:200'],
            'subtitle'     => ['nullable','string'],
            'button_text'  => ['nullable','string','max:100'],
            'button_url'   => ['nullable','string','max:255'],
            'bg_image'     => ['nullable','image','mimes:webp,jpg,jpeg,png','max:4096'],
            'bullets'      => ['nullable','array','max:6'],
            'bullets.*'    => ['nullable','string','max:255'],
            'sort_order'   => ['nullable','integer','min:0'],
            'is_active'    => ['sometimes','boolean'],
        ];
    }
}
