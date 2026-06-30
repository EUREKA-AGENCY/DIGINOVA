<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'description',
        'unit_price',
        'years',
        'discount_percent',
        'line_total',
        'sort_order',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public static function computeTotal(int $unitPrice, int $years, int $discountPercent): int
    {
        return (int) round($unitPrice * $years * (100 - $discountPercent) / 100);
    }
}
