<?php 

namespace App\Interfaces;

use App\Models\Product;

interface DiscountInterface
{
    public function calculate(Product $product): int;
}