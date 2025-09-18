<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        static $counter = 1;

        $productNames = [
            'Coffee Beans', 'Tea Leaves', 'Sugar', 'Milk', 'Bread', 'Butter', 'Cheese',
            'Yogurt', 'Eggs', 'Chicken', 'Beef', 'Pork', 'Fish', 'Rice', 'Pasta',
            'Tomatoes', 'Onions', 'Potatoes', 'Carrots', 'Lettuce'
        ];

        return [
            'sku'         => 'SKU-' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'barcode'     => '123456789' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'name'        => $productNames[($counter - 1) % count($productNames)],
            'description' => 'High quality ' . strtolower($productNames[($counter++ - 1) % count($productNames)]),
            'price'       => rand(500, 5000) / 100, // $5.00 to $50.00
            'stock'       => rand(10, 200),
            'status'      => true,
        ];
    }
}
