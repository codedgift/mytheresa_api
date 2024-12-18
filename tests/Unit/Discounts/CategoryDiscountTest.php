<?php

namespace Tests\Unit\Discounts;

use App\Discounts\CategoryDiscount;
use App\Models\Product;
use Tests\TestCase;

class CategoryDiscountTest extends TestCase
{
    public function test_calculate_discount_for_boots_category(): void
    {
        $product = new Product(['category' => 'boots', 'price' => 10000]);

        $discount = (new CategoryDiscount())->calculate($product);

        $this->assertEquals(3000, $discount);
    }

    public function test_no_discount_for_non_boots_category(): void
    {
        $product = new Product(['category' => 'sandals', 'price' => 10000]);

        $discount = (new CategoryDiscount())->calculate($product);

        $this->assertEquals(0, $discount);
    }

    public function test_discount_rate_for_boots_category(): void
    {
        $product = new Product(['category' => 'boots', 'price' => 10000]);

        $rate = $product->discount_rate;

        $this->assertEquals(config('discounts.category_discounts.boots'), $rate);
    }

    public function test_discount_rate_for_non_boots_category(): void
    {
        $product = new Product(['category' => 'sandals', 'price' => 10000]);

        $rate = $product->discount_rate;

        $this->assertNull($rate);
    }
}
