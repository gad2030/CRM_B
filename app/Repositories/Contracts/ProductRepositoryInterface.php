<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Product;

interface ProductRepositoryInterface
{
    /**
     * Get all products.
     */
    public function all(): Collection;

    /**
     * Find a product by ID.
     */
    public function find(int $id): ?Product;

    /**
     * Create a new product.
     */
    public function create(array $data): Product;

    /**
     * Update a product.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a product.
     */
    public function delete(int $id): bool;

    /**
     * Get products with category.
     */
    public function withCategory(): Collection;

    /**
     * Get products with price history.
     */
    public function withPrices(): Collection;

    /**
     * Get only active products.
     */
    public function getActiveProducts(): Collection;

    /**
     * Get products by category.
     */
    public function getByCategory(int $categoryId): Collection;
}
