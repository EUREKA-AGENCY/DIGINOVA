<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AssignMailDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by the 'admin' route middleware
    }

    public function rules(): array
    {
        return [
            'domain_id' => ['required', 'integer', 'exists:mail_domains,id'],
        ];
    }
}
