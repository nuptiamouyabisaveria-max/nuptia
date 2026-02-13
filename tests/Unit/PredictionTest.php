<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\StockMovement;
use App\Services\PredictionService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PredictionTest extends TestCase
{
    use RefreshDatabase;

    private PredictionService $predictionService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->predictionService = new PredictionService();
    }

    public function test_can_generate_prediction(): void
    {
        $product = Product::factory()->create();
        
        // Create some movements
        for ($i = 0; $i < 10; $i++) {
            StockMovement::factory()->create([
                'product_id' => $product->id,
                'type' => StockMovement::TYPE_EXIT,
                'quantity' => rand(5, 20),
            ]);
        }

        $prediction = $this->predictionService->generatePrediction($product, 7);

        $this->assertNotNull($prediction);
        $this->assertEquals($product->id, $prediction->product_id);
        $this->assertGreaterThanOrEqual(0, $prediction->predicted_quantity);
    }

    public function test_prediction_with_no_history(): void
    {
        $product = Product::factory()->create();
        
        $prediction = $this->predictionService->generatePrediction($product, 7);

        $this->assertEquals(0, $prediction->predicted_quantity);
        $this->assertEquals('No historical data available', $prediction->recommendation);
    }
}
