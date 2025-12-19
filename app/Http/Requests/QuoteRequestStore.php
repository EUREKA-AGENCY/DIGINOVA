<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequestStore extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id'   => ['nullable','integer','exists:services,id'],
            'project_type' => ['nullable','string','max:150'],
            'project_name' => ['nullable','string','max:150'],
            'budget_range' => ['nullable','string','max:100'],
            'deadline'     => ['nullable','string','max:100'],
            'details'      => ['nullable','string'],
            'company_name' => ['nullable','string','max:150'],
            'contact_name' => ['nullable','string','max:150'],
            'email'        => ['required','email','max:255'],
            'phone'        => ['nullable','string','max:50'],
            'meta'         => ['nullable','array'],
        ];
    }
}

