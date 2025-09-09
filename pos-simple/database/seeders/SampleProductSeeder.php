<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class SampleProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');
        
        $products = [
            [
                'sku' => 'ELEC001',
                'barcode' => '1234567890123',
                'name' => 'Wireless Bluetooth Headphones',
                'description' => 'Premium wireless headphones with noise cancellation',
                'price' => 199.99,
                'cost_price' => 120.00,
                'stock' => 25,
                'min_stock' => 5,
                'max_stock' => 100,
                'supplier' => 'TechCorp Ltd',
                'status' => true,
                'category_id' => $categories['Electronics']->id ?? null
            ],
            [
                'sku' => 'ELEC002',
                'barcode' => '1234567890124',
                'name' => 'Smartphone Charger Cable',
                'description' => 'Fast charging USB-C cable 6ft',
                'price' => 24.99,
                'cost_price' => 12.50,
                'stock' => 50,
                'min_stock' => 10,
                'max_stock' => 200,
                'supplier' => 'Cable Solutions Inc',
                'status' => true,
                'category_id' => $categories['Electronics']->id ?? null
            ],
            [
                'sku' => 'CLOTH001',
                'barcode' => '1234567890125',
                'name' => 'Cotton T-Shirt',
                'description' => '100% cotton unisex t-shirt, multiple colors',
                'price' => 19.99,
                'cost_price' => 8.00,
                'stock' => 3,  // Low stock to test alerts
                'min_stock' => 5,
                'max_stock' => 150,
                'supplier' => 'Fashion Forward',
                'status' => true,
                'category_id' => $categories['Clothing']->id ?? null
            ],
            [
                'sku' => 'FOOD001',
                'barcode' => '1234567890126',
                'name' => 'Organic Coffee Beans',
                'description' => 'Premium organic arabica coffee beans 1lb bag',
                'price' => 15.99,
                'cost_price' => 9.00,
                'stock' => 20,
                'min_stock' => 8,
                'max_stock' => 80,
                'supplier' => 'Bean There Coffee Co',
                'status' => true,
                'category_id' => $categories['Food & Beverages']->id ?? null
            ],
            [
                'sku' => 'HOME001',
                'barcode' => '1234567890127',
                'name' => 'LED Desk Lamp',
                'description' => 'Adjustable LED desk lamp with USB charging port',
                'price' => 49.99,
                'cost_price' => 28.00,
                'stock' => 15,
                'min_stock' => 3,
                'max_stock' => 50,
                'supplier' => 'Home Solutions',
                'status' => true,
                'category_id' => $categories['Home & Garden']->id ?? null
            ],
            [
                'sku' => 'BOOK001',
                'barcode' => '1234567890128',
                'name' => 'Programming Fundamentals Handbook',
                'description' => 'Comprehensive guide to programming basics',
                'price' => 39.99,
                'cost_price' => 20.00,
                'stock' => 12,
                'min_stock' => 2,
                'max_stock' => 30,
                'supplier' => 'Tech Books Publishing',
                'status' => true,
                'category_id' => $categories['Books & Media']->id ?? null
            ],
            [
                'sku' => 'SPORT001',
                'barcode' => '1234567890129',
                'name' => 'Yoga Mat Premium',
                'description' => 'Eco-friendly non-slip yoga mat 6mm thick',
                'price' => 29.99,
                'cost_price' => 15.00,
                'stock' => 8,
                'min_stock' => 5,
                'max_stock' => 40,
                'supplier' => 'Fitness Plus',
                'status' => true,
                'category_id' => $categories['Sports & Recreation']->id ?? null
            ],
            [
                'sku' => 'ELEC003',
                'barcode' => '1234567890130',
                'name' => 'Wireless Mouse',
                'description' => 'Ergonomic wireless optical mouse',
                'price' => 34.99,
                'cost_price' => 18.00,
                'stock' => 0,  // Out of stock to test alerts
                'min_stock' => 5,
                'max_stock' => 75,
                'supplier' => 'TechCorp Ltd',
                'status' => true,
                'category_id' => $categories['Electronics']->id ?? null
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
