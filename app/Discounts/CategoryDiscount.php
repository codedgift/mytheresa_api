<?php 

namespace App\Discounts;

use App\Interfaces\DiscountInterface;
use App\Models\Product;

class CategoryDiscount implements DiscountInterface
{
    public function calculate(Product $product): int
    {
        $discountRate = config('discounts.category_discounts')[$product->category] ?? 0;
        return (int)($product->price * $discountRate);
    }
}