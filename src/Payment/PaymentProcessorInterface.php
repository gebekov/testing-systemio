<?php

namespace App\Payment;

interface PaymentProcessorInterface
{
    /**
     * @throws PaymentFailedException в случае, если произошла ошибка при оплате
     * @param int $priceInCents
     * @return void
     */
    public function pay(int $priceInCents): void;
}
