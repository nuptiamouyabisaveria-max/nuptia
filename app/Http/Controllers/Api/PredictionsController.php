<?php

namespace App\Http\Controllers\Api;

use App\Models\Prediction;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PredictionsController extends BaseController
{
    /**
     * Get predictions for a product
     */
    public function index(Request $request): JsonResponse
    {
        $query = Prediction::with('product');

        if ($request->has('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        if ($request->has('period_days')) {
            $query->where('period_days', $request->get('period_days'));
        }

        $predictions = $query->orderBy('prediction_date', 'desc')
                             ->paginate($request->get('per_page', 15));

        return $this->sendSuccess($predictions);
    }

    /**
     * Generate prediction for a product
     */
    public function generate(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'period_days' => 'required|integer|in:7,30,90',
            'method' => 'nullable|in:linear_regression,moving_average,ml_light',
        ]);

        // Get historical data
        $movements = StockMovement::where('product_id', $product->id)
                                  ->where('type', StockMovement::TYPE_EXIT)
                                  ->orderBy('date', 'desc')
                                  ->limit(100)
                                  ->pluck('quantity', 'date')
                                  ->toArray();

        if (empty($movements)) {
            return $this->sendError([], 'Insufficient data for prediction', 400);
        }

        // Determine method
        $method = $validated['method'] ?? (count($movements) > 100 ? Prediction::METHOD_ML_LIGHT : Prediction::METHOD_MOVING_AVERAGE);

        // Calculate prediction
        $prediction = $this->calculatePrediction($product, $movements, $validated['period_days'], $method);

        return $this->sendSuccess($prediction, 'Prediction generated successfully', 201);
    }

    /**
     * Calculate prediction based on method
     */
    private function calculatePrediction(Product $product, array $movements, int $periodDays, string $method): Prediction
    {
        $quantities = array_values($movements);
        $avg_daily = array_sum($quantities) / count($quantities);

        $predictedQuantity = (int)round($avg_daily * $periodDays);
        $ruptureRisk = 0.0;
        $recommendation = "No action needed";

        if ($product->current_quantity <= $product->min_stock) {
            $ruptureRisk = 0.9;
            $recommendation = "ORDER {$predictedQuantity} units immediately";
        } elseif ($product->current_quantity - $predictedQuantity <= $product->min_stock) {
            $ruptureRisk = 0.5;
            $recommendation = "ORDER {$predictedQuantity} units within 5 days";
        }

        $prediction = Prediction::create([
            'product_id' => $product->id,
            'method' => $method,
            'period_days' => $periodDays,
            'predicted_quantity' => $predictedQuantity,
            'rupture_risk' => $ruptureRisk,
            'recommendation' => $recommendation,
            'confidence_score' => 0.75,
            'prediction_date' => now(),
            'forecast_data' => [
                'avg_daily_consumption' => $avg_daily,
                'last_movements' => array_slice($quantities, 0, 10),
            ],
        ]);

        return $prediction;
    }

    /**
     * Get prediction details
     */
    public function show(Prediction $prediction): JsonResponse
    {
        return $this->sendSuccess($prediction->load('product'));
    }
}
