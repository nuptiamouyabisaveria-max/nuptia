<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Product;

class AlertService
{
    /**
     * Check and generate alerts for a product
     */
    public function checkProductAlerts(Product $product): void
    {
        // Check minimum stock
        if ($product->current_quantity <= $product->min_stock) {
            $this->createAlert($product, Alert::TYPE_MIN_STOCK, 
                "Stock level ({$product->current_quantity}) has reached minimum ({$product->min_stock})", 
                Alert::SEVERITY_HIGH);
        }

        // Check for overstock
        if ($product->current_quantity > $product->optimal_stock * 1.5) {
            $this->createAlert($product, Alert::TYPE_OVERSTOCK,
                "Product is overstocked: {$product->current_quantity} units",
                Alert::SEVERITY_MEDIUM);
        }
    }

    /**
     * Create an alert if it doesn't exist
     */
    public function createAlert(Product $product, string $type, string $message, string $severity = Alert::SEVERITY_MEDIUM): Alert
    {
        // Check if similar alert exists
        $existingAlert = Alert::where('product_id', $product->id)
                             ->where('type', $type)
                             ->where('is_read', false)
                             ->orderBy('created_at', 'desc')
                             ->first();

        // Only create new alert if last one was created more than 1 hour ago
        if ($existingAlert && $existingAlert->created_at->diffInHours(now()) < 1) {
            return $existingAlert;
        }

        return Alert::create([
            'product_id' => $product->id,
            'type' => $type,
            'message' => $message,
            'severity' => $severity,
            'sent_at' => now(),
        ]);
    }

    /**
     * Check rupture risk based on prediction
     */
    public function checkRuptureRisk(Product $product, float $ruptureRisk): void
    {
        if ($ruptureRisk > 0.7) {
            $this->createAlert($product, Alert::TYPE_RUPTURE,
                "High risk of stock rupture in the coming days",
                Alert::SEVERITY_CRITICAL);
        } elseif ($ruptureRisk > 0.4) {
            $this->createAlert($product, Alert::TYPE_RUPTURE,
                "Moderate risk of stock rupture",
                Alert::SEVERITY_HIGH);
        }
    }

    /**
     * Get all pending alerts
     */
    public function getPendingAlerts($limit = 20)
    {
        return Alert::where('is_read', false)
                    ->orderBy('severity', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->with('product')
                    ->get();
    }
}
