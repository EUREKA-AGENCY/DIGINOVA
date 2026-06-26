<?php

namespace App\Http\Requests\Sms;

use Illuminate\Foundation\Http\FormRequest;

class SendSmsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->smsAccount !== null;
    }

    public function rules(): array
    {
        return [
            'sender' => ['required', 'string', 'max:11'],
            'destination' => ['required', 'string', 'regex:/^[0-9]{8,15}$/'],
            'message' => ['required', 'string', 'max:480'],
        ];
    }

    public function messages(): array
    {
        return [
            'sender.max' => "L'expéditeur est limité à 11 caractères.",
            'destination.regex' => 'Numéro au format international, sans le signe + (ex. 237674937152).',
            'message.max' => 'Le message est limité à 480 caractères (3 SMS).',
        ];
    }
}
