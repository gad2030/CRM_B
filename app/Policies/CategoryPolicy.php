<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine if the given category can be viewed by the user.
     */
    public function view(User $user, Category $category): bool
    {
        return $user->isAdmin() || $category->owner_id === $user->id;
    }

    /**
     * Determine if the given category can be updated by the user.
     */
    public function update(User $user, Category $category): bool
    {
        return $user->isAdmin() || $category->owner_id === $user->id;
    }

    /**
     * Determine if the given category can be deleted by the user.
     */
    public function delete(User $user, Category $category): bool
    {
        return $user->isAdmin() || $category->owner_id === $user->id;
    }
}
