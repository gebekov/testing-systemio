<?php

namespace App\DataFixtures;

use App\Coupon\CouponTypeEnum;
use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixture extends Fixture
{
    public const F15 = 'coupons.f15';
    public const P15 = 'coupons.p15';

    public function load(ObjectManager $manager): void
    {
        $this->fixedCoupon($manager);
        $this->percentCoupon($manager);

        $manager->flush();
    }

    private function fixedCoupon(ObjectManager $manager): void
    {
        $coupon = new Coupon();
        $coupon->code = 'F15';
        $coupon->type = CouponTypeEnum::Fixed;
        $coupon->value = 1500;

        $manager->persist($coupon);
        $this->referenceRepository->setReference(self::F15, $coupon);

    }

    private function percentCoupon(ObjectManager $manager): void
    {
        $coupon = new Coupon();
        $coupon->code = 'P15';
        $coupon->type = CouponTypeEnum::Percentage;
        $coupon->value = 0.15;

        $manager->persist($coupon);
        $this->referenceRepository->setReference(self::P15, $coupon);
    }
}
