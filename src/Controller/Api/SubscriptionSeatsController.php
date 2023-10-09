<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: 09.10.2026 ( 3 years after 2023.4 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Controller\Api;

use App\Controller\ValidationErrorResponseTrait;
use App\Dto\Request\Api\Subscription\AddSeats;
use App\Dto\Request\Api\Subscription\RemoveSeats;
use App\Entity\Subscription;
use App\Repository\SubscriptionRepositoryInterface;
use App\Subscription\UpdateAction\AddSeatToSubscription;
use App\Subscription\UpdateAction\RemoveSeatFromSubscription;
use Parthenon\Common\Exception\NoEntityFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionSeatsController
{
    use ValidationErrorResponseTrait;

    #[Route('/api/v1/subscription/{id}/seats/add', name: 'app_api_subscriptionseats_addseat', methods: ['POST'])]
    public function addSeat(
        Request $request,
        SerializerInterface $serializer,
        SubscriptionRepositoryInterface $subscriptionRepository,
        AddSeatToSubscription $addSeatToSubscription,
        ValidatorInterface $validator,
    ) {
        try {
            /** @var Subscription $subscription */
            $subscription = $subscriptionRepository->findById($request->get('id'));
        } catch (NoEntityFoundException $e) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }

        $dto = $serializer->deserialize($request->getContent(), AddSeats::class, 'json');
        $errors = $validator->validate($dto);
        $errorResponse = $this->handleErrors($errors);

        if ($errorResponse instanceof Response) {
            return $errorResponse;
        }

        $addSeatToSubscription->addSeats($subscription, $dto->getSeats());

        return new JsonResponse(['success' => true]);
    }

    #[Route('/api/v1/subscription/{id}/seats/remove', name: 'app_api_subscriptionseats_removeseat', methods: ['POST'])]
    public function removeSeat(
        Request $request,
        SerializerInterface $serializer,
        SubscriptionRepositoryInterface $subscriptionRepository,
        RemoveSeatFromSubscription $removeSeatFromSubscription,
        ValidatorInterface $validator,
    ) {
        try {
            /** @var Subscription $subscription */
            $subscription = $subscriptionRepository->findById($request->get('id'));
        } catch (NoEntityFoundException $e) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }

        $dto = $serializer->deserialize($request->getContent(), RemoveSeats::class, 'json');
        $dto->setSubscription($subscription);
        $errors = $validator->validate($dto);
        $errorResponse = $this->handleErrors($errors);

        if ($errorResponse instanceof Response) {
            return $errorResponse;
        }

        $removeSeatFromSubscription->removeSeats($subscription, $dto->getSeats());

        return new JsonResponse(['success' => true]);
    }
}
