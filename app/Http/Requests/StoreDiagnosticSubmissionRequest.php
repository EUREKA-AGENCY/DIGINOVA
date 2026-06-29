<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiagnosticSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:255'],
            'mail_situation' => ['required', Rule::in(['none', 'personal_email', 'own_domain_unmanaged'])],
            'mail_boxes_needed' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'sms_need' => ['required', Rule::in(['none', 'transactional', 'marketing', 'both'])],
            'sms_volume_monthly' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'main_need' => ['required', 'string', 'max:2000'],
            'budget_range' => ['nullable', 'string', 'max:100'],
        ];
    }
}
