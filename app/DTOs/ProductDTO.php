<?php

namespace App\DTOs;

use App\Models\Product;

class ProductDTO
{
    public function __construct(private Product $product) {}

    public function toArray(): array
    {
        return [
            'sku' => $this->product->sku,
            'name' => $this->product->name,
            'category' => $this->product->category,
            'price' => $this->product->calculated_price,
        ];
    }
}
