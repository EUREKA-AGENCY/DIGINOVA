<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Invoice extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_PAID = 'paid';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'invoice_number',
        'client_name',
        'client_email',
        'client_phone',
        'client_company',
        'client_bp',
        'client_siege_social',
        'client_address',
        'item_name',
        'unit_price',
        'years',
        'discount_percent',
        'total_amount',
        'status',
        'paid_at',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    /**
     * Renvoie les lignes à afficher dans le PDF.
     * Si des InvoiceItem existent, on les utilise ; sinon on retombe sur les colonnes legacy.
     */
    public function displayItems(): Collection
    {
        if ($this->relationLoaded('items') ? $this->items->isNotEmpty() : $this->items()->exists()) {
            return $this->items;
        }

        return collect([(object) [
            'description'      => $this->item_name,
            'unit_price'       => $this->unit_price,
            'years'            => $this->years,
            'discount_percent' => $this->discount_percent,
            'line_total'       => $this->total_amount,
        ]]);
    }

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Remise dégressive selon la durée d'engagement : 0% à 1 an, 5% à 2 ans, 10% à partir de 3 ans.
     */
    public static function discountPercentFor(int $years): int
    {
        if ($years >= 3) {
            return 10;
        }

        if ($years === 2) {
            return 5;
        }

        return 0;
    }

    public static function calculateTotal(int $unitPrice, int $years): array
    {
        $discountPercent = self::discountPercentFor($years);
        $subtotal = $unitPrice * $years;
        $total = (int) round($subtotal * (100 - $discountPercent) / 100);

        return ['discount_percent' => $discountPercent, 'total_amount' => $total];
    }

    public static function generateNumber(): string
    {
        $year = now()->format('Y');
        $count = self::where('invoice_number', 'like', "DGN-{$year}-%")->count() + 1;

        return sprintf('DGN-%s-%04d', $year, $count);
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }
}
