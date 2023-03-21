<?php

namespace App\Http\Controllers\Api\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicCategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ?Category $category = null): JsonResponse
    {
        if ($category !== null)
            return new JsonResponse(new CategoryResource($category));

        $categories = Category::query()
            ->paginate();

        return new JsonResponse(new CategoryCollection($categories));
    }
}
