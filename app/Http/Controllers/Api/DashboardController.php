<?php

namespace App\Http\Controllers\Api;

use App\Models\Alert;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    /**
     * Get dashboard statistics
     */
    public function index(Request $request): JsonResponse
    {
        $stats = [
            'total_products' => Product::count(),
            'total_stock_value' => Product::sum(\DB::raw('current_quantity * price')),
            'low_stock_products' => Product::where('current_quantity', '<=', \DB::raw('min_stock'))->count(),
            'total_movements' => StockMovement::count(),
            'pending_alerts' => Alert::where('is_read', false)->count(),
        ];

        return $this->sendSuccess($stats);
    }

    /**
     * Get products with low stock
     */
    public function lowStockProducts(Request $request): JsonResponse
    {
        $products = Product::where('current_quantity', '<=', \DB::raw('min_stock'))
                           ->with('category')
                           ->paginate($request->get('per_page', 10));

        return $this->sendSuccess($products);
    }

    /**
     * Get recent movements
     */
    public function recentMovements(Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        
        $movements = StockMovement::with('product', 'user')
                                  ->orderBy('date', 'desc')
                                  ->limit($limit)
                                  ->get();

        return $this->sendSuccess($movements);
    }

    /**
     * Get stock value by category
     */
    public function stockByCategory(): JsonResponse
    {
        $data = Product::selectRaw('categories.name, SUM(products.current_quantity * products.price) as total_value')
                       ->join('categories', 'products.category_id', '=', 'categories.id')
                       ->groupBy('categories.id', 'categories.name')
                       ->get();

        return $this->sendSuccess($data);
    }

    /**
     * Get movements trend
     */
    public function movementsTrend(Request $request): JsonResponse
    {
        $days = $request->get('days', 30);
        $startDate = now()->subDays($days);

        $data = StockMovement::selectRaw('DATE(date) as date, type, COUNT(*) as count, SUM(quantity) as total')
                             ->where('date', '>=', $startDate)
                             ->groupBy('date', 'type')
                             ->orderBy('date')
                             ->get();

        return $this->sendSuccess($data);
    }

    /**
     * Get rotation rate by product
     */
    public function rotationRate(Request $request): JsonResponse
    {
        $products = Product::with('movements')
                          ->get()
                          ->map(function ($product) {
                              $totalMovements = $product->movements->where('type', StockMovement::TYPE_EXIT)->sum('quantity');
                              $avgQuantity = $product->current_quantity ?: 1;
                              
                              return [
                                  'id' => $product->id,
                                  'name' => $product->name,
                                  'rotation_rate' => $avgQuantity > 0 ? round($totalMovements / $avgQuantity, 2) : 0,
                                  'total_sold' => $totalMovements,
                              ];
                          })
                          ->sortByDesc('rotation_rate')
                          ->values()
                          ->take($request->get('limit', 10));

        return $this->sendSuccess($products);
    }
}
