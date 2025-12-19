<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['required','string','max:255'],
            'category' => ['nullable','string','max:100'],
            'excerpt' => ['nullable','string'],
            'content' => ['nullable','string'],
            'icon' => ['nullable','string','max:100'],
            'is_active' => ['boolean'],
            'order' => ['integer','min:0'],
        ];
    }
}

