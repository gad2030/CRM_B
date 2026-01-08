<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories.
     */
    public function all(): Collection
    {
        return Category::all();
    }

    /**
     * Find a category by ID.
     */
    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    /**
     * Create a new category.
     */
    public function create(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Update a category.
     */
    public function update(int $id, array $data): bool
    {
        $category = $this->find($id);

        if (!$category) {
            return false;
        }

        return $category->update($data);
    }

    /**
     * Delete a category.
     */
    public function delete(int $id): bool
    {
        $category = $this->find($id);

        if (!$category) {
            return false;
        }

        return $category->delete();
    }

    /**
     * Get categories with products.
     */
    public function withProducts(): Collection
    {
        return Category::with('products')->get();
    }
}
