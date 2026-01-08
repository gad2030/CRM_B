<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    /**
     * Display a listing of categories.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = $request->has('with_products')
            ? $this->categoryRepository->withProducts()
            : $this->categoryRepository->all();

        return $this->ok(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }

    /**
     * Store a newly created category.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['owner_id'] = $request->user()->id;

        $category = $this->categoryRepository->create($data);

        return $this->ok(
            new CategoryResource($category),
            'Category created successfully',
            201
        );
    }

    /**
     * Display the specified category.
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->fail('Category not found', 404);
        }

        return $this->ok(
            new CategoryResource($category->load('products')),
            'Category retrieved successfully'
        );
    }

    /**
     * Update the specified category.
     */
    public function update(UpdateCategoryRequest $request, int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->fail('Category not found', 404);
        }

        $this->authorize('update', $category);

        $this->categoryRepository->update($id, $request->validated());

        return $this->ok(
            new CategoryResource($category->fresh()),
            'Category updated successfully'
        );
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return $this->fail('Category not found', 404);
        }

        $this->authorize('delete', $category);

        $this->categoryRepository->delete($id);

        return $this->ok(null, 'Category deleted successfully');
    }
}
