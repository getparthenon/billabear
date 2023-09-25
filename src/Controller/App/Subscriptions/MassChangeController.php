<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: 24.08.2026 ( 3 years after 1.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Controller\App\Subscriptions;

use App\Controller\App\CrudListTrait;
use App\Controller\ValidationErrorResponseTrait;
use App\DataMappers\PriceDataMapper;
use App\DataMappers\Settings\BrandSettingsDataMapper;
use App\DataMappers\Subscriptions\MassChangeDataMapper;
use App\DataMappers\Subscriptions\SubscriptionPlanDataMapper;
use App\Dto\Request\App\Subscription\MassChange\CreateMassChange;
use App\Dto\Response\App\Subscription\MassChange\CreateView;
use App\Repository\BrandSettingsRepositoryInterface;
use App\Repository\MassSubscriptionChangeRepositoryInterface;
use App\Repository\SubscriptionPlanRepositoryInterface;
use Parthenon\Billing\Repository\PriceRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MassChangeController
{
    use ValidationErrorResponseTrait;
    use CrudListTrait;

    #[IsGranted('ROLE_ACCOUNT_MANAGER')]
    #[Route('/app/subscription/mass-change/create', name: 'app_app_subscriptions_masschange_createchangeread', methods: ['GET'])]
    public function createChangeRead(
        Request $request,
        SerializerInterface $serializer,
        SubscriptionPlanRepositoryInterface $subscriptionPlanRepository,
        SubscriptionPlanDataMapper $subscriptionPlanDataMapper,
        PriceRepositoryInterface $priceRepository,
        PriceDataMapper $priceDataMapper,
        BrandSettingsRepositoryInterface $brandSettingsRepository,
        BrandSettingsDataMapper $brandSettingsDataMapper,
    ) {
        $prices = $priceRepository->getAll();
        $plans = $subscriptionPlanRepository->getAll();
        $brands = $brandSettingsRepository->getAll();

        $dto = new CreateView();
        $dto->setPlans(array_map([$subscriptionPlanDataMapper, 'createAppDto'], $plans));
        $dto->setPrices(array_map([$priceDataMapper, 'createAppDto'], $prices));
        $dto->setBrands(array_map([$brandSettingsDataMapper, 'createAppDto'], $brands));
        $json = $serializer->serialize($dto, 'json');

        return new JsonResponse($json, json: true);
    }

    #[IsGranted('ROLE_ACCOUNT_MANAGER')]
    #[Route('/app/subscription/mass-change', name: 'app_app_subscriptions_masschange_createchange', methods: ['POST'])]
    public function createChange(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        MassChangeDataMapper $changeDataMapper,
        MassSubscriptionChangeRepositoryInterface $massSubscriptionChangeRepository,
    ) {
        $dto = $serializer->deserialize($request->getContent(), CreateMassChange::class, 'json');
        $errors = $validator->validate($dto);
        $errorResponse = $this->handleErrors($errors);

        if ($errorResponse instanceof Response) {
            return $errorResponse;
        }

        $entity = $changeDataMapper->createEntity($dto);
        $massSubscriptionChangeRepository->save($entity);
        $outDto = $changeDataMapper->createAppDto($entity);
        $json = $serializer->serialize($outDto, 'json');

        return new JsonResponse($json, json: true);
    }

    #[Route('/app/subscription/mass-change', name: 'app_app_subscriptions_masschange_listchange', methods: ['GET'])]
    public function listChange(
        Request $request,
        SerializerInterface $serializer,
        MassChangeDataMapper $changeDataMapper,
        MassSubscriptionChangeRepositoryInterface $massSubscriptionChangeRepository,
    ) {
        return $this->crudList($request, $massSubscriptionChangeRepository, $serializer, $changeDataMapper, 'createdAt');
    }
}
