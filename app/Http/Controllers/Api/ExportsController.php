<?php

namespace App\Http\Controllers\Api;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockMovement;
use App\Services\ExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportsController extends BaseController
{
    private ExportService $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Export products as PDF
     */
    public function exportProductsPdf(Request $request): Response
    {
        $products = Product::with('category')->get();
        
        // For API, return JSON with download link
        return response()->json([
            'success' => true,
            'message' => 'PDF export generated',
            'data' => [
                'filename' => 'products.pdf',
                'url' => route('api.exports.download-products-pdf'),
            ],
        ]);
    }

    /**
     * Export products as Excel/CSV
     */
    public function exportProductsExcel(Request $request): Response
    {
        $products = Product::with('category')->get();
        $export = $this->exportService->exportProductsExcel($products);
        
        return response($export['content'], 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $export['filename'] . '"');
    }

    /**
     * Export stock movements as PDF
     */
    public function exportMovementsPdf(Request $request): Response
    {
        $movements = StockMovement::with('product', 'user')
                                  ->orderBy('date', 'desc')
                                  ->get();
        
        return response()->json([
            'success' => true,
            'message' => 'PDF export generated',
            'data' => [
                'filename' => 'stock_movements.pdf',
                'url' => route('api.exports.download-movements-pdf'),
            ],
        ]);
    }

    /**
     * Export stock movements as Excel/CSV
     */
    public function exportMovementsExcel(Request $request): Response
    {
        $movements = StockMovement::with('product', 'user')
                                  ->orderBy('date', 'desc')
                                  ->get();
        $export = $this->exportService->exportMovementsExcel($movements);
        
        return response($export['content'], 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $export['filename'] . '"');
    }

    /**
     * Export specific inventory as PDF
     */
    public function exportInventoryPdf(Request $request, Inventory $inventory): Response
    {
        return response()->json([
            'success' => true,
            'message' => 'PDF export generated',
            'data' => [
                'filename' => "inventory_{$inventory->id}.pdf",
                'url' => route('api.exports.download-inventory-pdf', ['inventory' => $inventory->id]),
            ],
        ]);
    }

    /**
     * Generate product fiche as PDF
     */
    public function generateProductFiche(Request $request, Product $product): Response
    {
        return response()->json([
            'success' => true,
            'message' => 'Product fiche generated',
            'data' => [
                'filename' => "fiche_{$product->barcode}.pdf",
                'url' => route('api.exports.product-fiche', ['product' => $product->id]),
            ],
        ]);
    }

    /**
     * Export dashboard data
     */
    public function exportDashboardData(Request $request)
    {
        $data = [
            'generated_at' => now()->toIso8601String(),
            'total_products' => Product::count(),
            'total_stock_value' => Product::sum(\DB::raw('current_quantity * price')),
            'low_stock_products' => Product::where('current_quantity', '<=', \DB::raw('min_stock'))->count(),
            'recent_movements' => StockMovement::with('product', 'user')
                                               ->orderBy('date', 'desc')
                                               ->limit(50)
                                               ->get(),
        ];

        $csv = "Generated At,Total Products,Stock Value,Low Stock\n";
        $csv .= "{$data['generated_at']},{$data['total_products']},{$data['total_stock_value']},{$data['low_stock_products']}\n";

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="dashboard_export.csv"');
    }
}
