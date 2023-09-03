<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
#[Entity(repositoryClass: ProductRepository::class)]
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
