<?php

namespace App\Coupon;

use Throwable;

class CouponNotFoundException extends \Exception
{
    public function __construct(string $coupon, int $code = 0, ?Throwable $previous = null)
    {
        $message = 'Coupon not found: ' . $coupon;
        parent::__construct($message, $code, $previous);
    }
}
