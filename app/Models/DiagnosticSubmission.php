<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosticSubmission extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_DONE = 'done';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'mail_situation',
        'mail_boxes_needed',
        'sms_need',
        'sms_volume_monthly',
        'main_need',
        'budget_range',
        'ai_analysis',
        'ai_status',
    ];
}
