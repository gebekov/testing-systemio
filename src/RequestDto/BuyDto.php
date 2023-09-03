<?php

namespace App\RequestDto;

use App\Validator\IsCouponExist;
use App\Validator\IsTaxNumber;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class BuyDto
{
    #[Assert\NotBlank]
    #[SerializedName('product')]
    public int $productID;

    #[Assert\NotBlank]
    #[IsTaxNumber]
    public string $taxNumber;

    #[Assert\NotBlank]
    #[IsCouponExist]
    public string $couponCode;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['stripe', 'paypal'])]
    public string $paymentProcessor;
}
