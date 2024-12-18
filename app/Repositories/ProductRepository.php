<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    private const PRODUCT_LIMIT = 5;

    public function getProducts(array $filters)
    {
        return Product::select(['sku', 'name', 'category', 'price'])
            ->when($filters['category'] ?? null, fn($q, $c) => $q->where('category', $c))
            ->when($filters['priceLessThan'] ?? null, fn($q, $p) => $q->where('price', '<=', $p))
            ->paginate(self::PRODUCT_LIMIT);
    }
}
