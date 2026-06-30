<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name'         => ['required', 'string', 'max:255'],
            'client_email'        => ['required', 'email', 'max:255'],
            'client_phone'        => ['nullable', 'string', 'max:50'],
            'client_company'      => ['nullable', 'string', 'max:255'],
            'client_bp'           => ['nullable', 'string', 'max:100'],
            'client_siege_social' => ['nullable', 'string', 'max:255'],
            'client_address'      => ['nullable', 'string', 'max:500'],
            'item_name'           => ['required', 'string', Rule::in([
                'VPS Basic Unmanaged',
                'VPS Premium Unmanaged',
                'VPS Business Unmanaged',
                'VPS Ultimate Unmanaged',
            ])],
            'years' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }
}
