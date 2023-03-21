<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Requests\Category\CategoryUpdateRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Category repository.
     */
    protected CategoryRepository $categoryRepository;

    /**
     * Create new instance.
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->authorizeResource(Category::class, 'category');
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->paginate();

        return new JsonResponse(new CategoryCollection($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request): JsonResponse
    {
        /** @var \App\Models\Category */
        $newCategory = DB::transaction(function () use ($request) {
            $newCategory = $this->categoryRepository
                ->create($request->validated());

            return $newCategory;
        });

        return new JsonResponse(new CategoryResource($newCategory), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return new JsonResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
    {
        /** @var \App\Models\Category */
        $updatedCategory = DB::transaction(function () use ($category, $request) {
            $updatedCategory = $this->categoryRepository
                ->update($category, $request->validated());

            return $updatedCategory;
        });

        return new JsonResponse(new CategoryResource($updatedCategory));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        DB::transaction(function () use ($category) {
            $deletedCategory = $this->categoryRepository
                ->softDelete($category);

            return $deletedCategory;
        });

        return new JsonResponse(null, 204);
    }
}
