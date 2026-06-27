<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMailDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by the 'admin' route middleware
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:mail_domains,name', 'regex:/^[a-z0-9.-]+\.[a-z]{2,}$/i'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'Format de nom de domaine invalide (ex. exemple.cm).',
            'name.unique' => 'Ce domaine est déjà enregistré.',
        ];
    }
}
