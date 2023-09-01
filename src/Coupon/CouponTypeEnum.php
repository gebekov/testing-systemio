<?php

namespace App\Coupon;

enum CouponTypeEnum: string
{
    case Fixed = 'F';
    case Percentage = 'P';
}
