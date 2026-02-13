<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriesController extends BaseController
{
    /**
     * Get all categories with hierarchy
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::with('subcategories')
            ->whereNull('parent_id')
            ->paginate($request->get('per_page', 15));

        return $this->sendSuccess($categories);
    }

    /**
     * Store a new category
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::create($validated);

        return $this->sendSuccess($category, 'Category created successfully', 201);
    }

    /**
     * Get single category
     */
    public function show(Category $category): JsonResponse
    {
        return $this->sendSuccess($category->load('subcategories', 'products'));
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($validated);

        return $this->sendSuccess($category, 'Category updated successfully');
    }

    /**
     * Delete category
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return $this->sendSuccess([], 'Category deleted successfully');
    }
}
