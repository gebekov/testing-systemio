<?php

namespace App\Tests\Payment;

use App\Payment\PaymentFailedException;
use App\Payment\StripePaymentProcessor;
use Generator;
use PHPUnit\Framework\TestCase;

class StripePaymentProcessorTest extends TestCase
{
    /**
     * @throws PaymentFailedException
     * @dataProvider providePay
     */
    public function testPay(
        int $priceInCents,
        bool $expectException
    ) {
        $processor = new StripePaymentProcessor(
            new \App\External\PaymentProcessor\StripePaymentProcessor()
        );

        if ($expectException) {
            $this->expectException(PaymentFailedException::class);
        } else {
            $this->expectNotToPerformAssertions();
        }

        $processor->pay($priceInCents);
    }

    public function providePay(): Generator
    {
        yield ['priceInCents' => 10 * 100, 'expectException' => true];
        yield ['priceInCents' => 101000 * 100, 'expectException' => false];
    }
}
