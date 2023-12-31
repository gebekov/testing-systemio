<?php

namespace App\External\PaymentProcessor;

use Exception;

class PaypalPaymentProcessor
{
    /**
     * @throws Exception in case of a failed payment
     */
    public function pay(int $price): void
    {
        if ($price > 100000) {
            throw new Exception('Too high price');
        }

        //process payment logic
    }
}
