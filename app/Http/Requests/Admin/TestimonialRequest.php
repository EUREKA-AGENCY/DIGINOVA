<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'author_name' => ['required','string','max:255'],
            'author_role' => ['nullable','string','max:255'],
            'company' => ['nullable','string','max:255'],
            'content' => ['required','string'],
            'rating' => ['nullable','integer','min:1','max:5'],
            'is_published' => ['boolean'],
        ];
    }
}

