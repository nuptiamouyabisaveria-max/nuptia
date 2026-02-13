<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_products(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        Product::factory(5)->create(['category_id' => $category->id]);

        $response = $this->actingAs($user, 'sanctum')
                        ->getJson('/api/v1/products');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_authenticated_user_can_create_product(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_MANAGER]);
        $category = Category::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                        ->postJson('/api/v1/products', [
                            'name' => 'Test Product',
                            'description' => 'Test Description',
                            'barcode' => 'TEST001',
                            'supplier' => 'Test Supplier',
                            'price' => 99.99,
                            'category_id' => $category->id,
                            'min_stock' => 5,
                            'optimal_stock' => 20,
                        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }
}
