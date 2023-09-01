<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
#[Entity]
class Product
{
    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: 'integer')]
    public int $id;

    #[Column(type: 'string')]
    public string $name;

    #[Column(type: 'integer')]
    public int $priceInCents;
}
