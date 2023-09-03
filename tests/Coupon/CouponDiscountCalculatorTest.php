<?php

namespace App\Tests\Coupon;

use App\Coupon\CouponDiscountCalculator;
use App\DataFixtures\CouponFixture;
use App\DataFixtures\ProductFixture;
use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\NotSupported;
use Exception;
use Generator;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CouponDiscountCalculatorTest extends KernelTestCase
{
    private AbstractDatabaseTool $databaseTool;
    private EntityManager $entityManager;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel(['environment' => 'test']);

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        self::ensureKernelShutdown();
        unset($this->databaseTool);
    }

    /**
     * @throws NotSupported
     * @throws Exception
     * @dataProvider calculateProvider
     */
    public function testCalculate(int $priceInCents, string $couponCode, int $expectDiscount, bool $expectException)
    {
        $this->databaseTool->loadFixtures([CouponFixture::class]);

        $calculator = new CouponDiscountCalculator(
            $this->entityManager->getRepository(Coupon::class)
        );

        if ($expectException) {
            $this->expectException(Exception::class);
        }

        $result = $calculator->calculate($couponCode, $priceInCents);
        $this->assertEquals($expectDiscount, $result);
    }

    public function calculateProvider(): Generator
    {
        yield [10000, 'F15', 1500, false];
        yield [10000, 'P15', 1500, false];
        yield [10000, 'D15', 0, true];
    }
}
