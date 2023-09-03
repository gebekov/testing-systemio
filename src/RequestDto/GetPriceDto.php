<?php

namespace App\RequestDto;

use App\Validator\IsTaxNumber;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class GetPriceDto
{
    #[Assert\NotBlank]
    #[SerializedName('product')]
    public int $productID;
    #[Assert\NotBlank]
    #[IsTaxNumber]
    public string $taxNumber;
    #[Assert\NotBlank]
    public string $couponCode;
}
