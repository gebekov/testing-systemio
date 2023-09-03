<?php

namespace App\Tests\Tax;

use App\Tax\TaxCalculator;
use Exception;
use Generator;
use PHPUnit\Framework\TestCase;

class TaxCalculatorTest extends TestCase
{
    /**
     * @throws Exception
     * @dataProvider provideCalculate
     */
    public function testCalculate(string $taxNumber, int $priceInCents, float $expect, bool $expectException): void
    {
        $calculator = new TaxCalculator(['DE' => 0.19]);
        if ($expectException) {
            $this->expectException(Exception::class);
        }
        $result = $calculator->calculate($taxNumber, $priceInCents);
        $this->assertEquals($expect, $result);
    }

    public function provideCalculate(): Generator
    {
        yield ['DE111111111', 10000, 1900, false];
        yield ['', 10000, 0, true];
        yield ['a', 10000, 0, true];
        yield ['1234', 10000, 0, true];
        yield ['RU123', 10000, 0, true];
    }
}
