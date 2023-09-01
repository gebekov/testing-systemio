<?php

namespace App\Entity;

use App\Coupon\CouponTypeEnum;
use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
#[Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[Id]
    #[Column(type: 'string')]
    public string $code;

    #[Column(type: 'string', enumType: CouponTypeEnum::class)]
    public CouponTypeEnum $type;

    #[Column(type: 'decimal')]
    public float $value;
}
