<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\ProductService;
use Database\Seeders\ProductSeeder;
use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    private const CURRENCY = 'EUR';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    protected function createProduct(array $attributes = []): Product
    {
        return Product::factory()->create($attributes);
    }

    protected function assertSuccessfulResponse($response, int $count = null): void
    {
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => ['sku', 'name', 'category', 'price'],
                ],
                'meta',
            ]);

        if ($count !== null) {
            $response->assertJsonCount($count, 'data');
        }
    }

    #[Test]
    public function it_returns_all_products_when_no_filters_are_applied()
    {
        $response = $this->getJson('/api/products');
        $this->assertSuccessfulResponse($response, 5);
    }

    #[Test]
    public function it_filters_products_by_category_and_price_less_than()
    {
        $this->createProduct(['sku' => '000001', 'category' => 'boots', 'price' => 50000]);
        $this->createProduct(['sku' => '000002', 'category' => 'boots', 'price' => 80000]);
        $this->createProduct(['sku' => '000003', 'category' => 'sandals', 'price' => 40000]);

        $response = $this->getJson('/api/products?category=boots&priceLessThan=60000');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.category', 'boots')
            ->assertJsonPath('data.0.price.original', 50000);
    }

    #[Test]
    public function it_paginates_products_correctly()
    {
        Product::factory()->count(12)->create();

        $response = $this->getJson('/api/products?page=2');

        $response->assertStatus(200)
            ->assertJsonPath('meta.current_page', 2)
            ->assertJsonPath('meta.per_page', 5);
    }

    #[Test]
    public function it_validates_filters()
    {
        $response = $this->getJson('/api/products?category=invalid&priceLessThan=invalid');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['priceLessThan']);
    }

    #[Test]
    public function it_returns_empty_data_when_no_products_match_filters()
    {
        $response = $this->getJson('/api/products?category=sandals&priceLessThan=1000');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Products retrieved successfully',
                'data' => [],
            ])
            ->assertJsonPath('meta.total', 0);
    }

    #[Test]
    public function it_handles_server_errors_gracefully()
    {
        $this->mock(ProductService::class)
            ->shouldReceive('getDiscountedProducts')
            ->once()
            ->andThrow(new Exception('Test server error'));

        $response = $this->getJson('/api/products');

        $response->assertStatus(500)
            ->assertJson([
                'status' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ]);
    }
}
