<?php

namespace App\Coupon;

use App\Repository\CouponRepository;
use Exception;

readonly class CouponDiscountCalculator
{
    public function __construct(
        private CouponRepository $couponRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function calculate(string $coupon, int $priceInCents): int
    {
        $coupon = $this->couponRepository->findOneByCode($coupon);

        if ($coupon === null) {
            throw new Exception('Coupon not found: ' . $coupon);
        }

        return match ($coupon->type) {
            CouponTypeEnum::Percentage => $priceInCents * $coupon->value,
            CouponTypeEnum::Fixed => $coupon->value
        };
    }
}
