<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users with different roles
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_MANAGER,
        ]);

        User::factory()->create([
            'name' => 'Observer User',
            'email' => 'observer@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_OBSERVER,
        ]);

        // Create categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic equipment and gadgets',
        ]);

        $computers = Category::create([
            'name' => 'Computers',
            'description' => 'Computer equipment',
            'parent_id' => $electronics->id,
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'description' => 'Computer accessories',
            'parent_id' => $electronics->id,
        ]);

        $furniture = Category::create([
            'name' => 'Furniture',
            'description' => 'Office furniture',
        ]);

        // Create products
        $products = [
            [
                'name' => 'Ordinateur portable Dell',
                'description' => 'Ordinateur portable haute performance',
                'barcode' => 'DELL001',
                'supplier' => 'Dell',
                'price' => 899.99,
                'category_id' => $computers->id,
                'min_stock' => 5,
                'optimal_stock' => 20,
                'current_quantity' => 15,
            ],
            [
                'name' => 'Souris USB',
                'description' => 'Souris sans fil',
                'barcode' => 'MOUSE001',
                'supplier' => 'Logitech',
                'price' => 29.99,
                'category_id' => $accessories->id,
                'min_stock' => 20,
                'optimal_stock' => 50,
                'current_quantity' => 35,
            ],
            [
                'name' => 'Clavier mécanique',
                'description' => 'Clavier mécanique RGB',
                'barcode' => 'KB001',
                'supplier' => 'Corsair',
                'price' => 149.99,
                'category_id' => $accessories->id,
                'min_stock' => 10,
                'optimal_stock' => 30,
                'current_quantity' => 8,
            ],
            [
                'name' => 'Chaise de bureau',
                'description' => 'Chaise de bureau ergonomique',
                'barcode' => 'CHAIR001',
                'supplier' => 'Herman Miller',
                'price' => 399.99,
                'category_id' => $furniture->id,
                'min_stock' => 3,
                'optimal_stock' => 10,
                'current_quantity' => 2,
            ],
            [
                'name' => 'Écran 27 pouces',
                'description' => 'Écran 4K',
                'barcode' => 'MON001',
                'supplier' => 'LG',
                'price' => 399.99,
                'category_id' => $computers->id,
                'min_stock' => 5,
                'optimal_stock' => 15,
                'current_quantity' => 12,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
