<?php

namespace App\Payment;

use Exception;
use Throwable;

class PaymentFailedException extends Exception
{
    public function __construct(
        string $message = 'Payment failed for unknown reasons',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
