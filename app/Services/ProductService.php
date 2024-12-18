<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Repositories\ProductRepository;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function getDiscountedProducts(array $filters): array
    {
        $products = $this->productRepository->getProducts($filters);

        return [
            'status' => true,
            'data' => $products->map(
                fn($product) => (new ProductDTO($product))->toArray()
            )->toArray(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ],
        ];
    }
}
