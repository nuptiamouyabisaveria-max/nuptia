<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'barcode' => $this->faker->unique()->ean13(),
            'supplier' => $this->faker->company(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'category_id' => Category::factory(),
            'min_stock' => $this->faker->numberBetween(5, 20),
            'optimal_stock' => $this->faker->numberBetween(50, 200),
            'current_quantity' => $this->faker->numberBetween(0, 100),
        ];
    }
}
