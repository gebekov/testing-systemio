<?php

namespace App\Tax;

use Exception;

readonly class TaxCalculator
{
    /**
     * @param array $taxMap массив с кодами стран и их налогами: ['DE' => 0.19]
     */
    public function __construct(
        private array $taxMap
    ) {
    }

    /**
     * @throws Exception
     */
    public function calculate(string $taxNumber, int $priceInCents): int
    {
        $country = substr($taxNumber, 0, 2);
        if ($country == '') {
            throw new Exception('Tax number is invalid');
        }

        $tax = $this->taxMap[$country] ?? null;
        if ($tax === null) {
            throw new Exception('Given country is unknown: ' . $country);
        }

        return $priceInCents * $tax;
    }
}
