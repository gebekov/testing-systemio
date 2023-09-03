<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixture extends Fixture
{
    public const IPHONE = 'products.iphone';
    public const HEADPHONES = 'products.headphones';
    public const PHONE_CASE = 'products.phone_case';

    public function load(ObjectManager $manager)
    {
        $iphone = new Product();
        $iphone->name = 'iPhone';
        $iphone->priceInCents = 100 * 100;
        $manager->persist($iphone);
        $this->referenceRepository->setReference(self::IPHONE, $iphone);

        $headphones = new Product();
        $headphones->name = 'Headphones';
        $headphones->priceInCents = 20 * 100;
        $manager->persist($headphones);
        $this->referenceRepository->setReference(self::HEADPHONES, $headphones);

        $phoneCase = new Product();
        $phoneCase->name = 'Phone Case';
        $phoneCase->priceInCents = 10 * 100;
        $manager->persist($phoneCase);
        $this->referenceRepository->setReference(self::PHONE_CASE, $phoneCase);

        $manager->flush();
    }
}
