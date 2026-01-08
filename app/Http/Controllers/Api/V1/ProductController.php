<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request): JsonResponse
    {
        $products = match (true) {
            $request->has('active_only') => $this->productRepository->getActiveProducts(),
            $request->has('category_id') => $this->productRepository->getByCategory($request->category_id),
            $request->has('with_prices') => $this->productRepository->withPrices(),
            default => $this->productRepository->withCategory()
        };

        return $this->ok(
            ProductResource::collection($products),
            'Products retrieved successfully'
        );
    }

    /**
     * Store a newly created product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['owner_id'] = $request->user()->id;

        $product = $this->productRepository->create($data);

        return $this->ok(
            new ProductResource($product->load('category')),
            'Product created successfully',
            201
        );
    }

    /**
     * Display the specified product.
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->fail('Product not found', 404);
        }

        return $this->ok(
            new ProductResource($product->load(['category', 'productPrices'])),
            'Product retrieved successfully'
        );
    }

    /**
     * Update the specified product.
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->fail('Product not found', 404);
        }

        $this->authorize('update', $product);

        $this->productRepository->update($id, $request->validated());

        return $this->ok(
            new ProductResource($product->fresh()->load('category')),
            'Product updated successfully'
        );
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            return $this->fail('Product not found', 404);
        }

        $this->authorize('delete', $product);

        $this->productRepository->delete($id);

        return $this->ok(null, 'Product deleted successfully');
    }
}
