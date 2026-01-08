<?php

namespace App\Policies;

use App\Models\Interaction;
use App\Models\User;

class InteractionPolicy
{
    /**
     * Determine if the user can update the interaction.
     */
    public function update(User $user, Interaction $interaction): bool
    {
        return $user->isAdmin() || $user->id === $interaction->user_id;
    }

    /**
     * Determine if the user can delete the interaction.
     */
    public function delete(User $user, Interaction $interaction): bool
    {
        return $user->isAdmin() || $user->id === $interaction->user_id;
    }
}

