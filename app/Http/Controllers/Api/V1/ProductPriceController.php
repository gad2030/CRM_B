<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\ProductPrice\StoreProductPriceRequest;
use App\Http\Requests\ProductPrice\UpdateProductPriceRequest;
use App\Http\Resources\ProductPriceResource;
use App\Repositories\Contracts\ProductPriceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductPriceController extends ApiController
{
    public function __construct(
        private readonly ProductPriceRepositoryInterface $productPriceRepository
    ) {
    }

    /**
     * Display a listing of product prices.
     */
    public function index(Request $request): JsonResponse
    {
        $productId = $request->query('product_id');

        $prices = $productId
            ? $this->productPriceRepository->getPriceHistory($productId)
            : $this->productPriceRepository->all();

        return $this->ok(
            ProductPriceResource::collection($prices),
            'Product prices retrieved successfully'
        );
    }

    /**
     * Store a newly created product price.
     */
    public function store(StoreProductPriceRequest $request): JsonResponse
    {
        $productPrice = $this->productPriceRepository->create($request->validated());

        return $this->ok(
            new ProductPriceResource($productPrice->load('product')),
            'Product price created successfully',
            201
        );
    }

    /**
     * Display the specified product price.
     */
    public function show(int $id): JsonResponse
    {
        $productPrice = $this->productPriceRepository->find($id);

        if (!$productPrice) {
            return $this->fail('Product price not found', 404);
        }

        return $this->ok(
            new ProductPriceResource($productPrice->load('product')),
            'Product price retrieved successfully'
        );
    }

    /**
     * Update the specified product price.
     */
    public function update(UpdateProductPriceRequest $request, int $id): JsonResponse
    {
        $productPrice = $this->productPriceRepository->find($id);

        if (!$productPrice) {
            return $this->fail('Product price not found', 404);
        }

        $this->authorize('update', $productPrice);

        $this->productPriceRepository->update($id, $request->validated());

        return $this->ok(
            new ProductPriceResource($productPrice->fresh()->load('product')),
            'Product price updated successfully'
        );
    }

    /**
     * Remove the specified product price.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $productPrice = $this->productPriceRepository->find($id);

        if (!$productPrice) {
            return $this->fail('Product price not found', 404);
        }

        $this->authorize('delete', $productPrice);

        $this->productPriceRepository->delete($id);

        return $this->ok(null, 'Product price deleted successfully');
    }
}
