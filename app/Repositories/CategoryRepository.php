<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    /**
     * Create new category from given input.
     */
    public function create(array $input): Category
    {
        $newCategory = Category::create([
            'slug'          => $input['slug'],
            'name'          => $input['name'],
            'description'   => $input['description'] ?? null,
        ]);

        return $newCategory;
    }

    /**
     * Update category from given input.
     */
    public function update(Category $category, array $input): Category
    {
        $category->update([
            'slug'          => $input['slug'] ?? $category->slug,
            'name'          => $input['name'] ?? $category->name,
            'description'   => $input['description'] ?? $category->description,
        ]);

        return $category;
    }

    /**
     * Soft delete the given category.
     */
    public function softDelete(Category $category): Category
    {
        $category->delete();

        return $category;
    }
}
