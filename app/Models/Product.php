<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    private const CURRENCY = 'EUR';

    protected $fillable = ['name', 'sku', 'category', 'price'];

    /**
     * Accessor for the calculated discount rate.
     * This returns the discount percentage as a rate (e.g., 0.3 for 30%).
     */
    public function getDiscountRateAttribute(): ?float
    {
        $categoryDiscounts = config('discounts.category_discounts');
        $skuDiscounts = config('discounts.sku_discounts');

        $categoryRate = $categoryDiscounts[$this->category] ?? null;
        $skuRate = $skuDiscounts[$this->sku] ?? null;

        if (is_null($categoryRate) && is_null($skuRate)) {
            return null;
        }

        return max($categoryRate ?? 0, $skuRate ?? 0);
    }

    /**
     * Accessor for the calculated price.
     * This includes original price, final price, and discount percentage.
     */
    public function getCalculatedPriceAttribute(): array
    {
        $originalPrice = $this->price;
        $discount = (int) ($originalPrice * $this->discount_rate);
        $finalPrice = $originalPrice - $discount;

        return [
            'original' => $originalPrice,
            'final' => $finalPrice,
            'discount_percentage' => $discount > 0
                ? round(($discount / $originalPrice) * 100) . '%'
                : null,
            'currency' => self::CURRENCY
        ];
    }
}
