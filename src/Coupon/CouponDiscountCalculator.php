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
     * @throws CouponNotFoundException
     */
    public function calculate(string $coupon, int $priceInCents): int
    {
        $coupon = $this->couponRepository->findOneByCode($coupon);

        if ($coupon === null) {
            throw new CouponNotFoundException($coupon);
        }

        return match ($coupon->type) {
            CouponTypeEnum::Percentage => $priceInCents * $coupon->value,
            CouponTypeEnum::Fixed => $coupon->value
        };
    }
}
