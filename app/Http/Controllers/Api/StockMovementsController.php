<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockMovementsController extends BaseController
{
    /**
     * Get all stock movements
     */
    public function index(Request $request): JsonResponse
    {
        $query = StockMovement::with('product', 'user');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [
                $request->get('start_date'),
                $request->get('end_date'),
            ]);
        }

        $movements = $query->orderBy('date', 'desc')
                           ->paginate($request->get('per_page', 15));

        return $this->sendSuccess($movements);
    }

    /**
     * Record a stock movement
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entry,exit',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
            'reference' => 'nullable|string',
            'date' => 'nullable|date',
        ]);

        $product = Product::find($validated['product_id']);

        // Update product quantity
        if ($validated['type'] === 'entry') {
            $product->current_quantity += $validated['quantity'];
        } else {
            $product->current_quantity -= $validated['quantity'];
            if ($product->current_quantity < 0) {
                return $this->sendError([], 'Insufficient stock', 400);
            }
        }
        $product->save();

        $validated['user_id'] = $request->user()->id;
        $validated['date'] = $validated['date'] ?? now();

        $movement = StockMovement::create($validated);

        return $this->sendSuccess($movement->load('product', 'user'), 'Movement recorded successfully', 201);
    }

    /**
     * Get single movement
     */
    public function show(StockMovement $movement): JsonResponse
    {
        return $this->sendSuccess($movement->load('product', 'user'));
    }
}
