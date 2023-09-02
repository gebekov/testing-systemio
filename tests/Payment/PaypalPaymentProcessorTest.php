<?php

namespace App\Tests\Payment;

use App\Payment\PaymentFailedException;
use App\Payment\PaypalPaymentProcessor;
use Generator;
use PHPUnit\Framework\TestCase;

class PaypalPaymentProcessorTest extends TestCase
{
    /**
     * @throws PaymentFailedException
     * @dataProvider providePay
     */
    public function testPay(
        int $priceInCents,
        bool $expectException
    ) {
        $processor = new PaypalPaymentProcessor(
            new \App\External\PaymentProcessor\PaypalPaymentProcessor()
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
        yield ['priceInCents' => 10 * 100, 'expectException' => false];
        yield ['priceInCents' => 101000 * 100, 'expectException' => true];
    }
}
