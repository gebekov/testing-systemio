<?php

namespace App\Tests\Validator;

use App\Entity\Coupon;
use App\Repository\CouponRepository;
use App\Validator\IsCouponExist;
use App\Validator\IsCouponExistValidator;
use App\Validator\IsTaxNumber;
use App\Validator\IsTaxNumberValidator;
use Generator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class IsCouponExistValidatorTest extends ConstraintValidatorTestCase
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
        $this->validator->validate($value, new IsCouponExist('myMessage'));

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
        yield ['value' => 'F15', 'expectViolation' => false];
        yield ['value' => 'P15', 'expectViolation' => false];
        yield ['value' => 'D15', 'expectViolation' => true];
    }

    protected function createValidator(): IsCouponExistValidator
    {
        $mock = $this->createMock(CouponRepository::class);
        $mock
            ->method('findOneByCode')
            ->willReturnCallback(
                function (string $code): ?Coupon {
                    if (in_array($code, ['F15', 'P15'])) {
                        // этого достаточно
                        return new Coupon();
                    }

                    return null;
                }
            );

        return new IsCouponExistValidator($mock);
    }
}
