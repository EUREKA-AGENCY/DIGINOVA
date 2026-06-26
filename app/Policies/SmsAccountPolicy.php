<?php

namespace App\Policies;

use App\Models\SmsAccount;
use App\Models\User;

class SmsAccountPolicy
{
    /**
     * Determine whether the user owns this SMS account.
     */
    public function manage(User $user, SmsAccount $smsAccount): bool
    {
        return $smsAccount->user_id === $user->id;
    }
}
