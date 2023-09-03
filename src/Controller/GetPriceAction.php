<?php

namespace App\Controller;

use App\Coupon\CouponNotFoundException;
use App\Price\PriceCalculator;
use App\Repository\ProductRepository;
use App\RequestDto\GetPriceDto;
use App\Tax\TaxFormatInvalidException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/v1/get-price', methods: 'POST')]
class GetPriceAction extends AbstractController
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
        private readonly ProductRepository $productRepository,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] GetPriceDto $getPriceDto,
    ): JsonResponse {
        $product = $this->productRepository->findOneByID($getPriceDto->productID);
        if ($product === null) {
            throw new NotFoundHttpException('Product not found');
        }

        try {
            $price = $this->priceCalculator->calculate(
                $product,
                $getPriceDto->taxNumber,
                $getPriceDto->couponCode ?? null
            );
        } catch (CouponNotFoundException) {
            throw new NotFoundHttpException('Coupon not found');
        } catch (TaxFormatInvalidException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $this->json(['price' => $price]);
    }
}
