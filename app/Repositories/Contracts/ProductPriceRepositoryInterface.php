<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\ProductPrice;

interface ProductPriceRepositoryInterface
{
    /**
     * Get all product prices.
     */
    public function all(): Collection;

    /**
     * Find a product price by ID.
     */
    public function find(int $id): ?ProductPrice;

    /**
     * Create a new product price.
     */
    public function create(array $data): ProductPrice;

    /**
     * Update a product price.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a product price.
     */
    public function delete(int $id): bool;

    /**
     * Get current active price for a product.
     */
    public function getActivePriceForProduct(int $productId): ?ProductPrice;

    /**
     * Get price history for a product.
     */
    public function getPriceHistory(int $productId): Collection;
}
