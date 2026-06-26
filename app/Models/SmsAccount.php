<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SmsAccount extends Model
{
    protected $fillable = [
        'user_id',
        'sender_name',
        'balance',
        'api_key',
    ];

    public function ensureApiKey(): string
    {
        if (! $this->api_key) {
            $this->forceFill(['api_key' => 'dgn_'.Str::random(40)])->save();
        }

        return $this->api_key;
    }

    public function regenerateApiKey(): string
    {
        $this->forceFill(['api_key' => 'dgn_'.Str::random(40)])->save();

        return $this->api_key;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SmsMessage::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(SmsContact::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(SmsCampaign::class);
    }
}
