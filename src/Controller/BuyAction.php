<?php

namespace App\Controller;

use App\Coupon\CouponNotFoundException;
use App\Payment\PaymentFailedException;
use App\Payment\PaymentService;
use App\Price\PriceCalculator;
use App\Repository\ProductRepository;
use App\RequestDto\BuyDto;
use App\RequestDto\GetPriceDto;
use App\Tax\TaxFormatInvalidException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/v1/buy', methods: 'POST')]
class BuyAction extends AbstractController
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
        private readonly ProductRepository $productRepository,
        private readonly PaymentService $paymentService,
    ) {
    }

    public function __invoke(
        #[MapRequestPayload] BuyDto $buyDto,
    ): JsonResponse {
        $product = $this->productRepository->findOneByID($buyDto->productID);
        if ($product === null) {
            throw new NotFoundHttpException('Product not found');
        }

        try {
            $price = $this->priceCalculator->calculate(
                $product,
                $buyDto->taxNumber,
                $buyDto->couponCode ?? null
            );
        } catch (CouponNotFoundException) {
            throw new NotFoundHttpException('Coupon not found');
        } catch (TaxFormatInvalidException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        try {
            $this->paymentService->pay($buyDto->paymentProcessor, $price);
        } catch (PaymentFailedException $e) {
            throw new BadRequestHttpException('Payment failed', $e);
        }

        return $this->json(['status' => 'ok']);
    }
}
