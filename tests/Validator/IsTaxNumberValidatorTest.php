<?php

namespace App\Tests\Validator;

use App\Validator\IsTaxNumber;
use App\Validator\IsTaxNumberValidator;
use Generator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class IsTaxNumberValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @param string $value
     * @param bool $expectViolation
     * @return void
     * @dataProvider provideValidatorValues
     */
    public function testValidate(
        string $value,
        bool $expectViolation
    ): void {
        $this->validator->validate($value, new IsTaxNumber('myMessage'));

        $violation = $this
            ->buildViolation('myMessage')
            ->setParameter('{{ string }}', $value);

        if ($expectViolation) {
            $violation->assertRaised();
        } else {
            $this->assertNoViolation();
        }
    }

    public function provideValidatorValues(): Generator
    {
        yield ['value' => 'DE123456789', 'expectViolation' => false];
        yield ['value' => 'FRAA123456789', 'expectViolation' => false];
        yield ['value' => 'IT12345678900', 'expectViolation' => false];
        yield ['value' => 'GR123456789', 'expectViolation' => false];

        yield ['value' => 'FR123456789', 'expectViolation' => true];
        yield ['value' => 'GR12345678900', 'expectViolation' => true];
        yield ['value' => 'UN123456789', 'expectViolation' => true];
    }

    protected function createValidator(): IsTaxNumberValidator
    {
        return new IsTaxNumberValidator(
            [
                'FR' => '/^FR[A-Z]{2}[0-9]{9}$/',
                'DE' => '/^DE[0-9]{9}$/',
                'IT' => '/^IT[0-9]{11}$/',
                'GR' => '/^GR[0-9]{9}$/',
            ]
        );
    }
}
