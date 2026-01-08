<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class AccountPolicy
{
    /**
     * Determine if the user can update the account.
     */
    public function update(User $user, Account $account): bool
    {
        return $user->isAdmin() || $user->id === $account->owner_id;
    }

    /**
     * Determine if the user can delete the account.
     */
    public function delete(User $user, Account $account): bool
    {
        return $user->isAdmin() || $user->id === $account->owner_id;
    }
}

