<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'title' => ['required','string','max:255'],
            'slug' => ['required','string','max:255'],
            'excerpt' => ['nullable','string'],
            'category' => ['nullable','string','max:100'],
            'tags' => ['nullable','array'],
            'content' => ['required','string'],
            'cover_image' => ['nullable','string','max:1024'],
            'published_at' => ['nullable','date'],
            'is_published' => ['boolean'],
            'author_id' => ['nullable','integer','exists:users,id'],
        ];
    }
}

