<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailAccount extends Model
{
    protected $fillable = [
        'mail_domain_id',
        'local_part',
    ];

    public function domain(): BelongsTo
    {
        return $this->belongsTo(MailDomain::class, 'mail_domain_id');
    }

    public function getAddressAttribute(): string
    {
        return "{$this->local_part}@{$this->domain->name}";
    }
}
