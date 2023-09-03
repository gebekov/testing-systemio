<?php

namespace App\Payment;

use App\Price\PriceCalculator;

readonly class PaymentService
{
    /**
     * @param array<PaymentProcessorInterface> $paymentProcessors
     */
    public function __construct(
        private array $paymentProcessors
    ) {
    }

    public function get(string $name): ?PaymentProcessorInterface
    {
        foreach ($this->paymentProcessors as $processor) {
            if ($processor->name() == $name) {
                return $processor;
            }
        }

        return null;
    }

    /**
     * @throws PaymentFailedException
     */
    public function pay(string $processorName, int $priceInCents): void
    {
        $processor = $this->get($processorName);

        if ($processor === null) {
            throw new \InvalidArgumentException('Processor not found: ' . $processorName);
        }

        $processor->pay($priceInCents);
    }
}
