<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class IsTaxNumber extends Constraint
{
    public string $message = 'The Tax number "{{ string }}" is not valid';
    public function __construct(
        string $message = null,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        $this->message = $message ?? $this->message;
        parent::__construct($options, $groups, $payload);
    }
}
