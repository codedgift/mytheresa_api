<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $skuCounter = 1;
        
        $products = [
            ['name' => 'BV Lean leather ankle boots', 'category' => 'boots', 'price' => 89000],
            ['name' => 'BV Lean leather ankle boots', 'category' => 'boots', 'price' => 99000],
            ['name' => 'Ashlington leather ankle boots', 'category' => 'boots', 'price' => 71000],
            ['name' => 'Naima embellished suede sandals', 'category' => 'sandals', 'price' => 79500],
            ['name' => 'Nathane leather sneakers', 'category' => 'sneakers', 'price' => 59000],
        ];

        // Cycle through predefined products based on the counter
        $product = $products[($skuCounter - 1) % count($products)];

        return [
            'sku' => str_pad($skuCounter++, 6, '0', STR_PAD_LEFT),
            'name' => $product['name'],
            'category' => $product['category'],
            'price' => $product['price'],
        ];
    }
}
