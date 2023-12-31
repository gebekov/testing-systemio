<?php

namespace App\Price;

use App\Coupon\CouponDiscountCalculator;
use App\Coupon\CouponNotFoundException;
use App\Entity\Product;
use App\Repository\CouponRepository;
use App\Tax\TaxCalculator;
use App\Tax\TaxFormatInvalidException;
use Exception;

readonly class PriceCalculator
{
    public function __construct(
        private TaxCalculator $taxCalculator,
        private CouponDiscountCalculator $couponDiscountCalculator,
    ) {
    }

    /**
     * @throws CouponNotFoundException
     * @throws TaxFormatInvalidException
     */
    public function calculate(Product $product, string $taxNumber, string $coupon = null): int
    {
        $price = $product->priceInCents;

        if ($coupon) {
            $price -= $this->couponDiscountCalculator->calculate($coupon, $price);
        }

        $price += $this->taxCalculator->calculate($taxNumber, $price);

        return $price;
    }
}
