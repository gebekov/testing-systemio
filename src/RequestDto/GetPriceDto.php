<?php

namespace App\RequestDto;

use App\Validator\IsCouponExist;
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
    #[IsCouponExist]
    public string $couponCode;
}
