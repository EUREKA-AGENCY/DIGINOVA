<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'item_name',
        'unit_price',
        'years',
        'discount_percent',
        'total_amount',
        'status',
        'paid_at',
    ];

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
