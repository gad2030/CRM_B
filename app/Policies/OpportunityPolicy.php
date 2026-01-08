<?php

namespace App\Policies;

use App\Models\Opportunity;
use App\Models\User;

class OpportunityPolicy
{
    /**
     * Determine if the user can update the opportunity.
     */
    public function update(User $user, Opportunity $opportunity): bool
    {
        return $user->isAdmin() || $user->id === $opportunity->owner_id;
    }

    /**
     * Determine if the user can delete the opportunity.
     */
    public function delete(User $user, Opportunity $opportunity): bool
    {
        return $user->isAdmin() || $user->id === $opportunity->owner_id;
    }
}

