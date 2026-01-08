<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products.
     */
    public function all(): Collection
    {
        return Product::all();
    }

    /**
     * Find a product by ID.
     */
    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Create a new product.
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update a product.
     */
    public function update(int $id, array $data): bool
    {
        $product = $this->find($id);

        if (!$product) {
            return false;
        }

        return $product->update($data);
    }

    /**
     * Delete a product.
     */
    public function delete(int $id): bool
    {
        $product = $this->find($id);

        if (!$product) {
            return false;
        }

        return $product->delete();
    }

    /**
     * Get products with category.
     */
    public function withCategory(): Collection
    {
        return Product::with('category')->get();
    }

    /**
     * Get products with price history.
     */
    public function withPrices(): Collection
    {
        return Product::with('productPrices')->get();
    }

    /**
     * Get only active products.
     */
    public function getActiveProducts(): Collection
    {
        return Product::where('is_active', true)->get();
    }

    /**
     * Get products by category.
     */
    public function getByCategory(int $categoryId): Collection
    {
        return Product::where('category_id', $categoryId)->get();
    }
}
