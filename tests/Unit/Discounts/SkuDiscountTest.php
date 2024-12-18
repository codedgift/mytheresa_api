<?php

namespace Tests\Unit\Discounts;

use App\Discounts\SkuDiscount;
use App\Models\Product;
use Tests\TestCase;

class SkuDiscountTest extends TestCase
{
    public function test_calculate_discount_for_specific_sku(): void
    {
        $product = new Product(['sku' => '000003', 'price' => 10000]);

        $discount = (new SkuDiscount())->calculate($product);

        $this->assertEquals(1500, $discount);
    }

    public function test_no_discount_for_non_matching_sku(): void
    {
        $product = new Product(['sku' => '000002', 'price' => 10000]);

        $discount = (new SkuDiscount())->calculate($product);

        $this->assertEquals(0, $discount);
    }

    public function test_no_discount_when_price_is_missing(): void
    {
        $product = new Product(['sku' => '000003']);

        $discount = (new SkuDiscount())->calculate($product);

        $this->assertEquals(0, $discount);
    }

    public function test_no_discount_when_sku_is_missing(): void
    {
        $product = new Product(['price' => 10000]);

        $discount = (new SkuDiscount())->calculate($product);

        $this->assertEquals(0, $discount);
    }

    public function test_calculate_discount_with_zero_price(): void
    {
        $product = new Product(['sku' => '000003', 'price' => 0]);

        $discount = (new SkuDiscount())->calculate($product);

        $this->assertEquals(0, $discount);
    }

    public function test_discount_rate_for_sku(): void
    {
        $product = new Product(['sku' => '000003', 'price' => 10000]);

        $rate = $product->discount_rate;

        $this->assertEquals(config('discounts.sku_discounts.000003'), $rate);
    }
}