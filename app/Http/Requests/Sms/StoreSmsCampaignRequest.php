<?php

namespace App\Http\Requests\Sms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSmsCampaignRequest extends FormRequest
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
            'sender' => ['required', 'string', 'max:11'],
            'message' => ['required', 'string', 'max:480'],
            'contact_ids' => ['required', 'array', 'min:1', 'max:50'],
            'contact_ids.*' => [
                'integer',
                Rule::exists('sms_contacts', 'id')->where('sms_account_id', $accountId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_ids.required' => 'Sélectionnez au moins un contact.',
            'contact_ids.max' => 'Une campagne est limitée à 50 destinataires pour le moment.',
        ];
    }
}
