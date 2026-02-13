<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Inventory;
use PDF;

class ExportService
{
    /**
     * Export products as PDF
     */
    public function exportProductsPdf($products)
    {
        $pdf = PDF::loadView('exports.products', ['products' => $products]);
        return $pdf->download('products.pdf');
    }

    /**
     * Export stock movements as PDF
     */
    public function exportMovementsPdf($movements)
    {
        $pdf = PDF::loadView('exports.movements', ['movements' => $movements]);
        return $pdf->download('stock_movements.pdf');
    }

    /**
     * Export inventory as PDF
     */
    public function exportInventoryPdf(Inventory $inventory)
    {
        $pdf = PDF::loadView('exports.inventory', ['inventory' => $inventory->load('details.product')]);
        return $pdf->download("inventory_{$inventory->id}.pdf");
    }

    /**
     * Generate product fiche as PDF
     */
    public function generateProductFiche(Product $product)
    {
        $product->load('category', 'movements', 'predictions', 'alerts');
        
        $pdf = PDF::loadView('exports.product_fiche', ['product' => $product]);
        return $pdf->download("fiche_{$product->barcode}.pdf");
    }

    /**
     * Export to CSV/Excel format
     */
    public function exportToExcel($data, $headers, $filename)
    {
        // This would typically use a library like Laravel Excel (Maatwebsite\Excel)
        // For now, we'll provide a CSV export method
        $csv = tmpfile();
        fputcsv($csv, $headers);
        
        foreach ($data as $row) {
            fputcsv($csv, $row);
        }
        
        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);
        
        return [
            'content' => $content,
            'filename' => $filename,
        ];
    }

    /**
     * Export products to Excel/CSV
     */
    public function exportProductsExcel($products)
    {
        $data = [];
        $headers = ['ID', 'Name', 'Category', 'Current Quantity', 'Min Stock', 'Optimal Stock', 'Price', 'Supplier'];
        
        foreach ($products as $product) {
            $data[] = [
                $product->id,
                $product->name,
                $product->category->name,
                $product->current_quantity,
                $product->min_stock,
                $product->optimal_stock,
                $product->price,
                $product->supplier,
            ];
        }
        
        return $this->exportToExcel($data, $headers, 'products.csv');
    }

    /**
     * Export stock movements to Excel/CSV
     */
    public function exportMovementsExcel($movements)
    {
        $data = [];
        $headers = ['ID', 'Product', 'Type', 'Quantity', 'Date', 'User', 'Reason'];
        
        foreach ($movements as $movement) {
            $data[] = [
                $movement->id,
                $movement->product->name,
                $movement->type,
                $movement->quantity,
                $movement->date->format('Y-m-d H:i'),
                $movement->user->name,
                $movement->reason,
            ];
        }
        
        return $this->exportToExcel($data, $headers, 'stock_movements.csv');
    }
}
