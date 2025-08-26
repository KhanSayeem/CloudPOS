<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku'         => strtoupper(fake()->unique()->bothify('SKU-####')),
            'barcode'     => fake()->optional()->ean13(),
            'name'        => fake()->words(2, true),
            'description' => fake()->optional()->sentence(),
            'price'       => fake()->randomFloat(2, 5, 500),
            'stock'       => fake()->numberBetween(0, 200),
            'status'      => true,
        ];
    }
}
