<?php

namespace App\Http\Requests\Mail;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMailAccountPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage', $this->route('domain'));
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'min:10', 'max:255', 'confirmed'],
        ];
    }
}
