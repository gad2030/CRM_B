<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;

class ContactPolicy
{
    /**
     * Determine if the user can update the contact.
     */
    public function update(User $user, Contact $contact): bool
    {
        return $user->isAdmin() || $user->id === $contact->owner_id;
    }

    /**
     * Determine if the user can delete the contact.
     */
    public function delete(User $user, Contact $contact): bool
    {
        return $user->isAdmin() || $user->id === $contact->owner_id;
    }
}

