<?php

namespace Tests\Unit\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    /** @var \Mockery\MockInterface|ProductRepository */
    protected ProductRepository $repositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(ProductRepository::class);
    }

    public function test_get_discounted_products(): void
    {
        $product = new Product([
            'sku' => '000001',
            'name' => 'Test Product',
            'category' => 'boots',
            'price' => 10000
        ]);

        $products = $this->createPaginatedProducts([$product]);

        $this->repositoryMock->shouldReceive('getProducts')
            ->once()
            ->withAnyArgs()
            ->andReturn($products);

        $service = new ProductService($this->repositoryMock);

        $result = $service->getDiscountedProducts([]);

        $this->assertArrayHasKey('data', $result, 'Response should contain a "data" key.');
        $this->assertCount(1, $result['data'], 'Data should contain exactly one product.');

        $this->assertEquals('000001', $result['data'][0]['sku'], 'Product SKU should match.');
        $this->assertEquals('Test Product', $result['data'][0]['name'], 'Product name should match.');
        $this->assertEquals('boots', $result['data'][0]['category'], 'Product category should match.');
        $this->assertEquals(10000, $result['data'][0]['price']['original'], 'Product price should match.');

        $this->assertArrayHasKey('meta', $result, 'Response should contain a "meta" key.');
        $this->assertEquals(1, $result['meta']['current_page'], 'Current page should be 1.');
        $this->assertEquals(1, $result['meta']['total'], 'Total count should be 1.');
        $this->assertEquals(5, $result['meta']['per_page'], 'Per page count should match.');
        $this->assertEquals(1, $result['meta']['last_page'], 'Last page should match.');
    }

    private function createPaginatedProducts(
        array $items = [],
        int $total = 1,
        int $perPage = 5,
        int $currentPage = 1
    ): LengthAwarePaginator {
        return new LengthAwarePaginator(
            collect($items),
            $total,
            $perPage,
            $currentPage
        );
    }

    public function test_it_returns_empty_data_when_category_does_not_exist(): void
    {
        $emptyPaginator = $this->createPaginatedProducts([], 0, 5, 1);

        $this->repositoryMock->shouldReceive('getProducts')
            ->once()
            ->with(['category' => 'nonexistent'])
            ->andReturn($emptyPaginator);

        $service = new ProductService($this->repositoryMock);

        $result = $service->getDiscountedProducts(['category' => 'nonexistent']);

        $this->assertTrue($result['status']);
        $this->assertCount(0, $result['data']);
        $this->assertEquals(0, $result['meta']['total']);
        $this->assertEquals(1, $result['meta']['current_page']);
        $this->assertEquals(5, $result['meta']['per_page']);
        $this->assertEquals(1, $result['meta']['last_page']);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close(); // Close Mockery to prevent memory leaks
    }
}
