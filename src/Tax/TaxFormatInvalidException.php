<?php

namespace App\Tax;

use Throwable;

class TaxFormatInvalidException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct('Tax number is invalid: ' . $message, $code, $previous);
    }
}
