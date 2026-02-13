<?php

namespace App\Services;

use App\Models\Prediction;
use App\Models\Product;
use App\Models\StockMovement;

class PredictionService
{
    /**
     * Generate prediction for a product
     */
    public function generatePrediction(Product $product, int $periodDays, ?string $method = null): Prediction
    {
        // Get historical data (exits only)
        $movements = StockMovement::where('product_id', $product->id)
                                  ->where('type', StockMovement::TYPE_EXIT)
                                  ->orderBy('date', 'desc')
                                  ->limit(100)
                                  ->get();

        if ($movements->isEmpty()) {
            // If no exits, create a default prediction
            return $this->createDefaultPrediction($product, $periodDays);
        }

        // Determine method based on data volume if not specified
        if (!$method) {
            $method = $movements->count() > 100 
                ? Prediction::METHOD_ML_LIGHT 
                : Prediction::METHOD_MOVING_AVERAGE;
        }

        return match ($method) {
            Prediction::METHOD_LINEAR_REGRESSION => $this->linearRegressionPrediction($product, $movements, $periodDays),
            Prediction::METHOD_MOVING_AVERAGE => $this->movingAveragePrediction($product, $movements, $periodDays),
            Prediction::METHOD_ML_LIGHT => $this->mlLightPrediction($product, $movements, $periodDays),
            default => $this->movingAveragePrediction($product, $movements, $periodDays),
        };
    }

    /**
     * Linear regression prediction
     */
    private function linearRegressionPrediction(Product $product, $movements, int $periodDays): Prediction
    {
        $quantities = $movements->pluck('quantity')->toArray();
        $n = count($quantities);

        // Calculate average daily consumption
        $avgDaily = array_sum($quantities) / $n;

        // Linear regression slope
        $x_mean = ($n - 1) / 2;
        $y_mean = $avgDaily;
        
        $numerator = 0;
        $denominator = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $numerator += ($i - $x_mean) * ($quantities[$i] - $y_mean);
            $denominator += pow(($i - $x_mean), 2);
        }
        
        $slope = $denominator > 0 ? $numerator / $denominator : 0;
        $predictedQuantity = (int)round($avgDaily * $periodDays + $slope * $periodDays);

        return $this->createPrediction($product, $predictedQuantity, $periodDays, 
            Prediction::METHOD_LINEAR_REGRESSION, $quantities);
    }

    /**
     * Moving average prediction
     */
    private function movingAveragePrediction(Product $product, $movements, int $periodDays): Prediction
    {
        $quantities = $movements->pluck('quantity')->toArray();
        
        // Calculate 7-day moving average
        $windowSize = min(7, count($quantities));
        $recentMovements = array_slice($quantities, 0, $windowSize);
        $avgDaily = array_sum($recentMovements) / $windowSize;
        
        $predictedQuantity = (int)round($avgDaily * $periodDays);

        return $this->createPrediction($product, $predictedQuantity, $periodDays,
            Prediction::METHOD_MOVING_AVERAGE, $quantities);
    }

    /**
     * ML Light prediction (simple regression on recent data)
     */
    private function mlLightPrediction(Product $product, $movements, int $periodDays): Prediction
    {
        // Use recent 30 days for better prediction
        $recentMovements = $movements->take(30)->pluck('quantity')->toArray();
        
        if (empty($recentMovements)) {
            return $this->createDefaultPrediction($product, $periodDays);
        }

        $avgDaily = array_sum($recentMovements) / count($recentMovements);
        
        // Apply smoothing factor based on trend
        $recent = array_slice($recentMovements, 0, 7);
        $older = array_slice($recentMovements, 7, 7);
        
        if (!empty($older)) {
            $recentAvg = array_sum($recent) / count($recent);
            $olderAvg = array_sum($older) / count($older);
            $trend = $olderAvg > 0 ? ($recentAvg / $olderAvg) : 1;
        } else {
            $trend = 1;
        }

        $predictedQuantity = (int)round($avgDaily * $periodDays * $trend);

        return $this->createPrediction($product, $predictedQuantity, $periodDays,
            Prediction::METHOD_ML_LIGHT, $recentMovements);
    }

    /**
     * Create prediction record
     */
    private function createPrediction(Product $product, int $predictedQuantity, int $periodDays,
                                     string $method, array $historicalData): Prediction
    {
        $ruptureRisk = $this->calculateRuptureRisk($product, $predictedQuantity);
        $recommendation = $this->getRecommendation($product, $predictedQuantity, $ruptureRisk);

        return Prediction::create([
            'product_id' => $product->id,
            'method' => $method,
            'period_days' => $periodDays,
            'predicted_quantity' => max(0, $predictedQuantity),
            'rupture_risk' => $ruptureRisk,
            'recommendation' => $recommendation,
            'confidence_score' => 0.85,
            'prediction_date' => now(),
            'forecast_data' => [
                'avg_daily' => array_sum($historicalData) / count($historicalData),
                'last_10_movements' => array_slice($historicalData, 0, 10),
            ],
        ]);
    }

    /**
     * Create default prediction when no history
     */
    private function createDefaultPrediction(Product $product, int $periodDays): Prediction
    {
        return Prediction::create([
            'product_id' => $product->id,
            'method' => Prediction::METHOD_MOVING_AVERAGE,
            'period_days' => $periodDays,
            'predicted_quantity' => 0,
            'rupture_risk' => 0,
            'recommendation' => 'No historical data available',
            'confidence_score' => 0,
            'prediction_date' => now(),
            'forecast_data' => [],
        ]);
    }

    /**
     * Calculate rupture risk
     */
    private function calculateRuptureRisk(Product $product, int $predictedQuantity): float
    {
        if ($product->current_quantity <= $product->min_stock) {
            return 0.9; // Critical
        }

        $daysToRupture = $product->current_quantity - $product->min_stock;
        $predictedDailyConsumption = $predictedQuantity > 0 ? $predictedQuantity / 30 : 0;

        if ($predictedDailyConsumption > 0) {
            $daysRemaining = $daysToRupture / $predictedDailyConsumption;
            return min(1, max(0, 1 - ($daysRemaining / 30)));
        }

        return 0;
    }

    /**
     * Get recommendation text
     */
    private function getRecommendation(Product $product, int $predictedQuantity, float $ruptureRisk): string
    {
        if ($ruptureRisk > 0.8) {
            return "ORDER {$predictedQuantity} units IMMEDIATELY - Risk of rupture";
        } elseif ($ruptureRisk > 0.5) {
            return "ORDER {$predictedQuantity} units within 3-5 days";
        } elseif ($product->current_quantity > $product->optimal_stock) {
            return "No urgent action needed - Stock is above optimal level";
        } else {
            return "Monitor stock closely - No urgent action needed";
        }
    }
}
