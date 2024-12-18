<?php

namespace Tests\Unit\Repositories;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_products_with_category_filter(): void
    {
        Product::factory()->create(['category' => 'boots']);
        Product::factory()->create(['category' => 'sandals']);

        $repository = new ProductRepository();
        $result = $repository->getProducts(['category' => 'boots']);

        $this->assertCount(1, $result);
        $this->assertEquals('boots', $result->first()->category);
    }

    public function test_get_products_with_price_filter(): void
    {
        Product::factory()->create(['price' => 5000]);
        Product::factory()->create(['price' => 15000]);

        $repository = new ProductRepository();
        $result = $repository->getProducts(['priceLessThan' => 10000]);

        $this->assertCount(1, $result);
        $this->assertLessThanOrEqual(10000, $result->first()->price);
    }

    public function test_get_products_with_combined_filters(): void
    {
        Product::factory()->create(['category' => 'boots', 'price' => 5000]);
        Product::factory()->create(['category' => 'boots', 'price' => 15000]);
        Product::factory()->create(['category' => 'sandals', 'price' => 5000]);

        $repository = new ProductRepository();
        $result = $repository->getProducts(['category' => 'boots', 'priceLessThan' => 10000]);

        $this->assertCount(1, $result);
        $this->assertEquals('boots', $result->first()->category);
        $this->assertLessThanOrEqual(10000, $result->first()->price);
    }

    public function test_get_products_with_pagination_limit(): void
    {
        Product::factory()->count(10)->create();

        $repository = new ProductRepository();
        $result = $repository->getProducts([]);

        $this->assertCount(5, $result);
    }

    public function test_get_products_with_no_matching_results(): void
    {
        Product::factory()->create(['category' => 'boots']);

        $repository = new ProductRepository();
        $result = $repository->getProducts(['category' => 'non-existent-category']);

        $this->assertCount(0, $result);
    }

    public function test_get_products_with_no_filters(): void
    {
        Product::factory()->count(3)->create();

        $repository = new ProductRepository();
        $result = $repository->getProducts([]);

        $this->assertCount(3, $result);
    }

    public function test_get_products_with_price_edge_case(): void
    {
        Product::factory()->create(['price' => 10000]);

        $repository = new ProductRepository();
        $result = $repository->getProducts(['priceLessThan' => 10000]);

        $this->assertCount(1, $result);
        $this->assertEquals(10000, $result->first()->price);
    }
}
