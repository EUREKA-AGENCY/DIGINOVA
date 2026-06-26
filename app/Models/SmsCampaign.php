<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsCampaign extends Model
{
    protected $fillable = [
        'sms_account_id',
        'name',
        'sender',
        'message',
        'status',
        'recipients_count',
        'sent_count',
        'failed_count',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(SmsAccount::class, 'sms_account_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SmsMessage::class, 'sms_campaign_id');
    }
}
