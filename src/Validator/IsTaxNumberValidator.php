<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsTaxNumberValidator extends ConstraintValidator
{
    /**
     * @param array<string, string> $countriesTaxPatterns список кодов стран и регулярных выражений, которые должны
     *                                         проходить проверку: [ 'FR' => '/$FR[A-Z]{2}[0-9]{9}^/' ]
     */
    public function __construct(
        private readonly array $countriesTaxPatterns
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsTaxNumber) {
            throw new UnexpectedTypeException($constraint, IsTaxNumber::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // конфигурация с доступными паттернами tax номеров находится здесь:
        // config/services.yaml#parameters.app.countries_tax_patterns
        foreach ($this->countriesTaxPatterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return;
            }
        }

        // сюда попадаем в том случае, если ни один паттерн не прошел проверку
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
