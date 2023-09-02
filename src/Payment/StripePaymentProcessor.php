<?php

namespace App\Payment;

use App\External\PaymentProcessor\StripePaymentProcessor as ExternalStripePaymentProcessor;

readonly class StripePaymentProcessor implements PaymentProcessorInterface
{
    public function __construct(
        private ExternalStripePaymentProcessor $processor
    ) {
    }

    public function pay(int $priceInCents): void
    {
        $ok = $this->processor->processPayment($priceInCents / 100);
        if (!$ok) {
            throw new PaymentFailedException();
        }
    }
}
