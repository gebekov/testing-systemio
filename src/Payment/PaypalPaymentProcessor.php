<?php

namespace App\Payment;

use App\External\PaymentProcessor\PaypalPaymentProcessor as ExternalPaypalPaymentProcessor;

readonly class PaypalPaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(
        private ExternalPaypalPaymentProcessor $processor
    ) {
    }

    public function pay(int $priceInCents): void
    {
        try {
            $this->processor->pay($priceInCents / 100);
        } catch (\Exception $e) {
            throw new PaymentFailedException($e->getMessage(), previous: $e);
        }
    }

    public function name(): string
    {
        return 'paypal';
    }
}
