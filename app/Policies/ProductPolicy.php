<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    /**
     * Determine if the given product can be viewed by the user.
     */
    public function view(User $user, Product $product): bool
    {
        return $user->isAdmin() || $product->owner_id === $user->id;
    }

    /**
     * Determine if the given product can be updated by the user.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->isAdmin() || $product->owner_id === $user->id;
    }

    /**
     * Determine if the given product can be deleted by the user.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->isAdmin() || $product->owner_id === $user->id;
    }
}
