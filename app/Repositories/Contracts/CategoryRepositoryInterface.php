<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     */
    public function all(): Collection;

    /**
     * Find a category by ID.
     */
    public function find(int $id): ?Category;

    /**
     * Create a new category.
     */
    public function create(array $data): Category;

    /**
     * Update a category.
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete a category.
     */
    public function delete(int $id): bool;

    /**
     * Get categories with products.
     */
    public function withProducts(): Collection;
}
