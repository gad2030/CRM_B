<?php

namespace App\Repositories;

use App\Models\ProductPrice;
use App\Repositories\Contracts\ProductPriceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductPriceRepository implements ProductPriceRepositoryInterface
{
    /**
     * Get all product prices.
     */
    public function all(): Collection
    {
        return ProductPrice::all();
    }

    /**
     * Find a product price by ID.
     */
    public function find(int $id): ?ProductPrice
    {
        return ProductPrice::find($id);
    }

    /**
     * Create a new product price.
     */
    public function create(array $data): ProductPrice
    {
        return ProductPrice::create($data);
    }

    /**
     * Update a product price.
     */
    public function update(int $id, array $data): bool
    {
        $productPrice = $this->find($id);

        if (!$productPrice) {
            return false;
        }

        return $productPrice->update($data);
    }

    /**
     * Delete a product price.
     */
    public function delete(int $id): bool
    {
        $productPrice = $this->find($id);

        if (!$productPrice) {
            return false;
        }

        return $productPrice->delete();
    }

    /**
     * Get current active price for a product.
     */
    public function getActivePriceForProduct(int $productId): ?ProductPrice
    {
        return ProductPrice::where('product_id', $productId)
            ->active()
            ->orderBy('starts_at', 'desc')
            ->first();
    }

    /**
     * Get price history for a product.
     */
    public function getPriceHistory(int $productId): Collection
    {
        return ProductPrice::where('product_id', $productId)
            ->orderBy('starts_at', 'desc')
            ->get();
    }
}
