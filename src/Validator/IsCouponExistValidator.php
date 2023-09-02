<?php

namespace App\Validator;

use App\Repository\CouponRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsCouponExistValidator extends ConstraintValidator
{
    public function __construct(
        private readonly CouponRepository $couponRepository
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsCouponExist) {
            throw new UnexpectedTypeException($constraint, IsCouponExist::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $coupon = $this->couponRepository->findOneByCode($value);

        if ($coupon !== null) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
