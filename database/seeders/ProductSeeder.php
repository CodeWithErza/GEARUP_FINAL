<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the product seeder.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Baby Car Seat',
                'sku' => '121038412',
                'category_id' => 1,
                'description' => null,
                'image' => 'images/products/1745730748.jpg',
                'price' => 1000.00,
                'stock' => 19,
                'reorder_level' => 5,
                'unit' => 'piece',
                'brand' => 'Baby Products',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ],
            [
                'name' => 'Steering Wheel',
                'sku' => '12213',
                'category_id' => 1,
                'description' => 'adsclqb',
                'image' => 'images/products/1745730828.jpg',
                'price' => 1500.00,
                'stock' => 18,
                'reorder_level' => 5,
                'unit' => 'piece',
                'brand' => 'Test',
                'model' => 'Test',
                'manufacturer' => 'test',
                'is_active' => true
            ],
            [
                'name' => 'White Car Seat',
                'sku' => '3829401',
                'category_id' => 1,
                'description' => 'Test',
                'image' => 'images/products/1745730896.jpg',
                'price' => 5000.00,
                'stock' => 8,
                'reorder_level' => 7,
                'unit' => 'piece',
                'brand' => 'Test',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ],
            [
                'name' => 'Black Car Bumper',
                'sku' => '12345678',
                'category_id' => 2,
                'description' => 'Test',
                'image' => 'images/products/1745730975.jpg',
                'price' => 500.00,
                'stock' => 9,
                'reorder_level' => 5,
                'unit' => 'piece',
                'brand' => 'Test',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ],
            [
                'name' => 'Black Car Wiper',
                'sku' => '1234567',
                'category_id' => 2,
                'description' => 'Test',
                'image' => 'images/products/1745745818.jpg',
                'price' => 1000.00,
                'stock' => 10,
                'reorder_level' => 5,
                'unit' => 'piece',
                'brand' => 'Wiper',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ],
            [
                'name' => 'Car Alternator',
                'sku' => '121234',
                'category_id' => 3,
                'description' => 'Test',
                'image' => 'images/products/1745745874.jpg',
                'price' => 2500.00,
                'stock' => 10,
                'reorder_level' => 4,
                'unit' => 'piece',
                'brand' => 'Engine',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ],
            [
                'name' => 'Fuel Injection System',
                'sku' => '3425678986342',
                'category_id' => 3,
                'description' => 'Test',
                'image' => 'images/products/1745745922.jpg',
                'price' => 1000.00,
                'stock' => 12,
                'reorder_level' => 4,
                'unit' => 'piece',
                'brand' => 'Test',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ],
            [
                'name' => 'Auto Steering Rack',
                'sku' => '12408124',
                'category_id' => 4,
                'description' => 'Test',
                'image' => 'images/products/1745745971.jpg',
                'price' => 1500.00,
                'stock' => 10,
                'reorder_level' => 3,
                'unit' => 'piece',
                'brand' => 'test',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ],
            [
                'name' => 'Automotive disc',
                'sku' => '12910412',
                'category_id' => 4,
                'description' => 'Test',
                'image' => 'images/products/1745746018.jpg',
                'price' => 1300.00,
                'stock' => 15,
                'reorder_level' => 5,
                'unit' => 'piece',
                'brand' => 'Test',
                'model' => 'Test',
                'manufacturer' => 'Test',
                'is_active' => true
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 