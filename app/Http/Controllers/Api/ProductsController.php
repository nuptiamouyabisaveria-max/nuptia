<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductsController extends BaseController
{
    /**
     * Get all products with filters
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with('category');

        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
        }

        if ($request->has('low_stock') && $request->get('low_stock')) {
            $query->whereRaw('current_quantity <= min_stock');
        }

        $products = $query->paginate($request->get('per_page', 15));

        return $this->sendSuccess($products);
    }

    /**
     * Store a new product
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|unique:products',
            'supplier' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'min_stock' => 'required|integer|min:0',
            'optimal_stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($validated);

        return $this->sendSuccess($product, 'Product created successfully', 201);
    }

    /**
     * Get single product
     */
    public function show(Product $product): JsonResponse
    {
        return $this->sendSuccess($product->load('category', 'movements', 'predictions'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|unique:products,barcode,' . $product->id,
            'supplier' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'min_stock' => 'sometimes|integer|min:0',
            'optimal_stock' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        return $this->sendSuccess($product, 'Product updated successfully');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return $this->sendSuccess([], 'Product deleted successfully');
    }
}
