<?php

namespace App\Http\Requests\Site;

use App\Models\JoinRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JoinRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'applicant_name'  => ['required', 'string', 'max:150'],
            'applicant_phone' => ['required', 'string', 'max:30', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'applicant_email' => ['nullable', 'email', 'max:150'],
            'company_name'    => ['nullable', 'string', 'max:200'],
            'source'          => ['required', 'string', Rule::in(JoinRequest::SOURCES)],
            'referrer_name'   => ['nullable', 'string', 'max:150', 'required_if:source,friend_employee'],
        ];
    }

    public function messages(): array
    {
        return [
            'applicant_name.required'  => __('admin.join_requests.validation.applicant_name_required'),
            'applicant_name.max'       => __('admin.join_requests.validation.applicant_name_max'),
            'applicant_phone.required' => __('admin.join_requests.validation.applicant_phone_required'),
            'applicant_phone.regex'    => __('admin.join_requests.validation.applicant_phone_invalid'),
            'applicant_email.email'    => __('admin.join_requests.validation.applicant_email_invalid'),
            'source.required'          => __('admin.join_requests.validation.source_required'),
            'source.in'                => __('admin.join_requests.validation.source_invalid'),
            'referrer_name.required_if'=> __('admin.join_requests.validation.referrer_required'),
            'referrer_name.max'        => __('admin.join_requests.validation.referrer_max'),
        ];
    }
}
