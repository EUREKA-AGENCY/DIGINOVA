<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsContact extends Model
{
    protected $fillable = [
        'sms_account_id',
        'name',
        'phone',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(SmsAccount::class, 'sms_account_id');
    }
}
