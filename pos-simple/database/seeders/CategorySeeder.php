<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories',
                'active' => true
            ],
            [
                'name' => 'Clothing',
                'description' => 'Apparel and fashion items',
                'active' => true
            ],
            [
                'name' => 'Food & Beverages',
                'description' => 'Food items and drinks',
                'active' => true
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement and garden supplies',
                'active' => true
            ],
            [
                'name' => 'Books & Media',
                'description' => 'Books, magazines, and media content',
                'active' => true
            ],
            [
                'name' => 'Sports & Recreation',
                'description' => 'Sports equipment and recreational items',
                'active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
