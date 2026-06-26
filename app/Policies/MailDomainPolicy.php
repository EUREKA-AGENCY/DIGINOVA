<?php

namespace App\Policies;

use App\Models\MailDomain;
use App\Models\User;

class MailDomainPolicy
{
    /**
     * Determine whether the user owns the domain and can manage its mail accounts.
     */
    public function manage(User $user, MailDomain $mailDomain): bool
    {
        return $mailDomain->owner_user_id !== null
            && $mailDomain->owner_user_id === $user->id;
    }
}
