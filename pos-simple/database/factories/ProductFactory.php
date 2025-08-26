<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sku'         => strtoupper($this->faker->unique()->bothify('SKU-####')),
            'barcode'     => $this->faker->optional()->ean13(),
            'name'        => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price'       => $this->faker->randomFloat(2, 5, 500),
            'stock'       => $this->faker->numberBetween(5, 200),
            'status'      => true,
        ];
    }
}
