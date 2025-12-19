<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'product_type',
        'sku',
        'short_description',
        'description',
        'price',
        'rent_price',
        'stock',
        'images',
        'attributes',
        'rental_terms',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
    ];
}

