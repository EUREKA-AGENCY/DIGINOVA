<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsMessage extends Model
{
    public const STATUS_SENT = 'sent';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'sms_account_id',
        'sms_campaign_id',
        'sender',
        'destination',
        'message',
        'segments',
        'status',
        'provider_response',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(SmsAccount::class, 'sms_account_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(SmsCampaign::class, 'sms_campaign_id');
    }
}
