<?php

namespace App\Tests\Price;

use App\Coupon\CouponDiscountCalculator;
use App\DataFixtures\CouponFixture;
use App\DataFixtures\ProductFixture;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Price\PriceCalculator;
use App\Tax\TaxCalculator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Exception;
use Generator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PriceCalculatorTest extends KernelTestCase
{
    private EntityManager $entityManager;
    private AbstractDatabaseTool $databaseTool;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel(['environment' => 'test']);

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    protected function tearDown(): void
    {
        self::ensureKernelShutdown();
        unset($this->databaseTool);
    }

    /**
     * @throws Exception
     * @dataProvider calculateProvider
     */
    public function testCalculate(string $productRef, string $taxNumber, string $couponCode, float $expect)
    {
        $executor = $this->databaseTool->loadFixtures([CouponFixture::class, ProductFixture::class]);
        $referenceRepository = $executor->getReferenceRepository();

        $calculator = new PriceCalculator(
            new TaxCalculator(['GR' => 0.24]),
            new CouponDiscountCalculator(
                $this->entityManager->getRepository(Coupon::class)
            ),
        );

        $product = $referenceRepository->getReference($productRef);
        assert($product instanceof Product);

        $result = $calculator->calculate($product, $taxNumber, $couponCode);
        $this->assertEquals($expect, $result);
    }

    public function calculateProvider(): Generator
    {
        yield [ProductFixture::IPHONE, 'GR123456789', 'P15', 10540];
    }
}
