<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductDTO;
use App\Models\Product;
use Tests\TestCase;

class ProductDTOTest extends TestCase
{
    public function test_dto_transforms_product_with_discount(): void
    {
        $product = new Product(['sku' => '000003', 'name' => 'Test Product', 'category' => 'boots', 'price' => 10000]);
        $product->discount_rate = config('discounts.category_discounts.boots');

        $dto = new ProductDTO($product);
        $result = $dto->toArray();

        $this->assertEquals([
            'sku' => '000003',
            'name' => 'Test Product',
            'category' => 'boots',
            'price' => [
                'original' => 10000,
                'final' => 7000,
                'discount_percentage' => '30%',
                'currency' => 'EUR',
            ],
        ], $result);
    }

    public function test_dto_transforms_product_without_discount(): void
    {
        $product = new Product(['sku' => '000002', 'name' => 'Test Product', 'category' => 'sandals', 'price' => 10000]);
        $product->discount_rate = 0.0;

        $dto = new ProductDTO($product);
        $result = $dto->toArray();

        $this->assertEquals([
            'sku' => '000002',
            'name' => 'Test Product',
            'category' => 'sandals',
            'price' => [
                'original' => 10000,
                'final' => 10000,
                'discount_percentage' => null,
                'currency' => 'EUR',
            ],
        ], $result);
    }
}