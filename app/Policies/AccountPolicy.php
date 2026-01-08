<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class AccountPolicy
{
    /**
     * Determine if the user can view the account.
     */
    public function view(User $user, Account $account): bool
    {
        // Check employer match
        if (!$user->currentEmployer || $account->employer_id !== $user->currentEmployer->id) {
            return false;
        }

        // Owner or has view permission
        return $user->id === $account->owner_id || 
               $user->isOwnerOf($user->currentEmployer) || 
               $user->hasPermission('view_accounts');
    }

    /**
     * Determine if the user can create accounts.
     */
    public function create(User $user): bool
    {
        if (!$user->currentEmployer) {
            return false;
        }

        return $user->isOwnerOf($user->currentEmployer) || 
               $user->hasPermission('create_accounts');
    }

    /**
     * Determine if the user can update the account.
     */
    public function update(User $user, Account $account): bool
    {
        // Check employer match
        if (!$user->currentEmployer || $account->employer_id !== $user->currentEmployer->id) {
            return false;
        }

        // Owner or has edit permission
        return $user->id === $account->owner_id || 
               $user->isOwnerOf($user->currentEmployer) || 
               $user->hasPermission('edit_accounts');
    }

    /**
     * Determine if the user can delete the account.
     */
    public function delete(User $user, Account $account): bool
    {
        // Check employer match
        if (!$user->currentEmployer || $account->employer_id !== $user->currentEmployer->id) {
            return false;
        }

        // Owner or has delete permission
        return $user->id === $account->owner_id || 
               $user->isOwnerOf($user->currentEmployer) || 
               $user->hasPermission('delete_accounts');
    }
}

