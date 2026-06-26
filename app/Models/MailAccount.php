<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailAccount extends Model
{
    public const TYPE_MAILBOX = 'mailbox';

    public const TYPE_ALIAS = 'alias';

    protected $fillable = [
        'mail_domain_id',
        'local_part',
        'type',
        'forward_to',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(MailDomain::class, 'mail_domain_id');
    }

    public function getAddressAttribute(): string
    {
        return "{$this->local_part}@{$this->domain->name}";
    }

    public function isMailbox(): bool
    {
        return $this->type === self::TYPE_MAILBOX;
    }
}
