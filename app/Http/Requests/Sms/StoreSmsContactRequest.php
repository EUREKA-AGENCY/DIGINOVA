<?php

namespace App\Http\Requests\Sms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSmsContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->smsAccount !== null;
    }

    public function rules(): array
    {
        $accountId = $this->user()->smsAccount->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'regex:/^[0-9]{8,15}$/',
                Rule::unique('sms_contacts', 'phone')->where('sms_account_id', $accountId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Numéro au format international, sans le signe + (ex. 237674937152).',
            'phone.unique' => 'Ce contact existe déjà.',
        ];
    }
}
