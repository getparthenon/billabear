<?php

/*
 * Copyright Iain Cambridge 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: TBD ( 3 years after 1.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Controller\App;

use App\Dto\Request\App\PostSubscriptionPlan;
use App\Dto\Response\App\SubscriptionPlanCreationInfo;
use App\Factory\FeatureFactory;
use App\Factory\PriceFactory;
use App\Factory\SubscriptionPlanFactory;
use Parthenon\Billing\Entity\Product;
use Parthenon\Billing\Repository\PriceRepositoryInterface;
use Parthenon\Billing\Repository\ProductRepositoryInterface;
use Parthenon\Billing\Repository\SubscriptionFeatureRepositoryInterface;
use Parthenon\Billing\Repository\SubscriptionPlanRepositoryInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionPlanController
{
    #[Route('/app/product/{id}/plan-creation', name: 'app_product_plan_create_info', methods: ['get'])]
    public function planCreationInfo(
        Request $request,
        ProductRepositoryInterface $productRepository,
        SubscriptionFeatureRepositoryInterface $subscriptionFeatureRepository,
        FeatureFactory $featureFactory,
        PriceRepositoryInterface $priceRepository,
        PriceFactory $priceFactory,
        SerializerInterface $serializer
    ): Response {
        try {
            /** @var Product $product */
            $product = $productRepository->getById($request->get('id'));
        } catch (NoEntityFoundException $e) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }

        $features = $subscriptionFeatureRepository->getAll();
        $prices = $priceRepository->getAllForProduct($product);

        $featureDtos = array_map([$featureFactory, 'createAppDto'], $features);
        $priceDtos = array_map([$priceFactory, 'createAppDto'], $prices);

        $dto = new SubscriptionPlanCreationInfo();
        $dto->setPrices($priceDtos);
        $dto->setFeatures($featureDtos);

        $json = $serializer->serialize($dto, 'json');

        return new JsonResponse($json, json: true);
    }

    #[Route('/app/product/{id}/plan', name: 'app_product_plan_create', methods: ['POST'])]
    public function createPlan(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        SubscriptionPlanFactory $factory,
        ProductRepositoryInterface $productRepository,
        SubscriptionPlanRepositoryInterface $subscriptionPlanRepository,
    ) {
        try {
            /** @var Product $product */
            $product = $productRepository->getById($request->get('id'));
        } catch (NoEntityFoundException $e) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }

        /** @var PostSubscriptionPlan $dto */
        $dto = $serializer->deserialize($request->getContent(), PostSubscriptionPlan::class, 'json');
        $errors = $validator->validate($dto);

        if (count($errors) > 0) {
            $errorOutput = [];
            foreach ($errors as $error) {
                $propertyPath = $error->getPropertyPath();
                $errorOutput[$propertyPath] = $error->getMessage();
            }

            return new JsonResponse([
                'errors' => $errorOutput,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $plan = $factory->createFromPostSubscriptionPlan($dto);
        $plan->setProduct($product);
        $subscriptionPlanRepository->save($plan);
        $dto = $factory->createAppDto($plan);
        $jsonResponse = $serializer->serialize($dto, 'json');

        return new JsonResponse($jsonResponse, JsonResponse::HTTP_CREATED, json: true);
    }
}
