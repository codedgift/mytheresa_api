<?php

namespace App\Discounts;

use App\Interfaces\DiscountInterface;
use App\Models\Product;

class SkuDiscount implements DiscountInterface
{
    public function calculate(Product $product): int
    {
        $discountRate = config('discounts.sku_discounts')[$product->sku] ?? 0;
        return (int)($product->price * $discountRate);
    }
}
