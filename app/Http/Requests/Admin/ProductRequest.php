<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'slug' => ['required','string','max:255'],
            'category_id' => ['nullable','integer','exists:categories,id'],
            'product_type' => ['required','in:sale,rental,service'],
            'sku' => ['nullable','string','max:100'],
            'short_description' => ['nullable','string'],
            'description' => ['nullable','string'],
            'price' => ['nullable','numeric','min:0'],
            'rent_price' => ['nullable','numeric','min:0'],
            'stock' => ['integer','min:0'],
            'images' => ['nullable','array'],
            'attributes' => ['nullable','array'],
            'rental_terms' => ['nullable','string'],
            'is_active' => ['boolean'],
        ];
    }
}

