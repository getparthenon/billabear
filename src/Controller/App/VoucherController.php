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

use App\Controller\ValidationErrorResponseTrait;
use App\Dto\Request\App\Voucher\CreateVoucher;
use App\Dto\Response\Api\ListResponse;
use App\Factory\VoucherFactory;
use App\Repository\VoucherRepositoryInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VoucherController
{
    use ValidationErrorResponseTrait;

    #[Route('/app/voucher', name: 'app_app_voucher_listvoucher', methods: ['GET'])]
    public function listVoucher(
        Request $request,
        SerializerInterface $serializer,
        VoucherFactory $voucherFactory,
        VoucherRepositoryInterface $voucherRepository
    ): Response {
        $lastKey = $request->get('last_key');
        $resultsPerPage = (int) $request->get('limit', 10);

        if ($resultsPerPage < 1) {
            return new JsonResponse([
                'reason' => 'limit is below 1',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($resultsPerPage > 100) {
            return new JsonResponse([
                'reason' => 'limit is above 100',
            ], JsonResponse::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }
        // TODO add filters
        $filters = [];

        $resultSet = $voucherRepository->getList(
            filters: $filters,
            limit: $resultsPerPage,
            lastId: $lastKey,
        );

        $dtos = array_map([$voucherFactory, 'createAppDto'], $resultSet->getResults());

        $listResponse = new ListResponse();
        $listResponse->setHasMore($resultSet->hasMore());
        $listResponse->setData($dtos);
        $listResponse->setLastKey($resultSet->getLastKey());

        $json = $serializer->serialize($listResponse, 'json');

        return new JsonResponse($json, json: true);
    }

    #[Route('/app/voucher', name: 'app_app_voucher_createvoucher', methods: ['POST'])]
    public function createVoucher(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        VoucherRepositoryInterface $voucherRepository,
        VoucherFactory $voucherFactory,
        Security $security,
    ) {
        $createVoucher = $serializer->deserialize($request->getContent(), CreateVoucher::class, 'json');
        $errors = $validator->validate($createVoucher);
        $errorResponse = $this->handleErrors($errors);

        if ($errorResponse instanceof Response) {
            return $errorResponse;
        }

        $entity = $voucherFactory->createEntity($createVoucher);
        $entity->setBillingAdmin($security->getUser());
        $voucherRepository->save($entity);
        $dto = $voucherFactory->createAppDto($entity);
        $json = $serializer->serialize($dto, 'json');

        return new JsonResponse($json, JsonResponse::HTTP_CREATED, json: true);
    }
}
