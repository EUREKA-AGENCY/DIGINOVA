<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdjustSmsCreditsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // gated by the 'admin' route middleware
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:-100000', 'max:100000'],
        ];
    }
}
