<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use App\Models\InventoryDetail;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoriesController extends BaseController
{
    /**
     * Get all inventories
     */
    public function index(Request $request): JsonResponse
    {
        $query = Inventory::with('user', 'details.product');

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $inventories = $query->orderBy('date', 'desc')
                             ->paginate($request->get('per_page', 15));

        return $this->sendSuccess($inventories);
    }

    /**
     * Create a new inventory
     */
    public function store(Request $request): JsonResponse
    {
        // accept missing date from client and default to now()
        $validated = $request->validate([
            'date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        // default date when not provided by frontend
        $validated['date'] = $request->get('date', now()->toDateTimeString());
        $validated['user_id'] = $request->user()->id;
        $validated['status'] = Inventory::STATUS_PENDING;

        $inventory = Inventory::create($validated);

        return $this->sendSuccess($inventory, 'Inventory created successfully', 201);
    }

    /**
     * Get single inventory
     */
    public function show(Inventory $inventory): JsonResponse
    {
        return $this->sendSuccess($inventory->load('user', 'details.product'));
    }

    /**
     * Add items to inventory
     */
    public function addItems(Request $request, Inventory $inventory): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.counted_quantity' => 'required|integer|min:0',
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            
            $detail = InventoryDetail::create([
                'inventory_id' => $inventory->id,
                'product_id' => $item['product_id'],
                'counted_quantity' => $item['counted_quantity'],
                'system_quantity' => $product->current_quantity,
                'variance' => $item['counted_quantity'] - $product->current_quantity,
            ]);
        }

        return $this->sendSuccess([], 'Items added to inventory', 201);
    }

    /**
     * Complete inventory and adjust stock
     */
    public function complete(Request $request, Inventory $inventory): JsonResponse
    {
        $validated = $request->validate([
            'justifications' => 'nullable|array',
        ]);

        foreach ($inventory->details as $detail) {
            if ($detail->variance !== 0) {
                $product = $detail->product;
                $product->current_quantity = $detail->counted_quantity;
                $product->save();

                if (isset($validated['justifications'][$detail->id])) {
                    $detail->justification = $validated['justifications'][$detail->id];
                    $detail->save();
                }
            }
        }

        $inventory->status = Inventory::STATUS_COMPLETED;
        $inventory->save();

        return $this->sendSuccess($inventory, 'Inventory completed successfully');
    }

    /**
     * Archive inventory
     */
    public function archive(Inventory $inventory): JsonResponse
    {
        $inventory->status = Inventory::STATUS_ARCHIVED;
        $inventory->save();

        return $this->sendSuccess($inventory, 'Inventory archived successfully');
    }
}
