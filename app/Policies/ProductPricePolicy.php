<?php

namespace App\Policies;

use App\Models\ProductPrice;
use App\Models\User;

class ProductPricePolicy
{
    /**
     * Determine if the given product price can be viewed by the user.
     */
    public function view(User $user, ProductPrice $productPrice): bool
    {
        return $user->isAdmin() || $productPrice->product->owner_id === $user->id;
    }

    /**
     * Determine if the given product price can be updated by the user.
     */
    public function update(User $user, ProductPrice $productPrice): bool
    {
        return $user->isAdmin() || $productPrice->product->owner_id === $user->id;
    }

    /**
     * Determine if the given product price can be deleted by the user.
     */
    public function delete(User $user, ProductPrice $productPrice): bool
    {
        return $user->isAdmin() || $productPrice->product->owner_id === $user->id;
    }
}
