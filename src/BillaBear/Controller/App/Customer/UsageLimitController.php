<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Controller\App\Customer;

use BillaBear\Controller\ValidationErrorResponseTrait;
use BillaBear\DataMappers\Usage\UsageLimitDataMapper;
use BillaBear\Dto\Request\App\Usage\CreateUsageLimit;
use BillaBear\Entity\Customer;
use BillaBear\Repository\CustomerRepositoryInterface;
use BillaBear\Repository\UsageLimitRepositoryInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Parthenon\Common\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsageLimitController
{
    use LoggerAwareTrait;
    use ValidationErrorResponseTrait;

    #[IsGranted('ROLE_CUSTOMER_SUPPORT')]
    #[Route('/app/customer/{id}/usage-limit', name: 'app_customer_usage_limit', methods: ['POST'])]
    public function createUsageLimit(
        Request $request,
        CustomerRepositoryInterface $customerRepository,
        UsageLimitRepositoryInterface $usageLimitRepository,
        UsageLimitDataMapper $usageLimitDataMapper,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
    ): Response {
        $this->getLogger()->info('Received request to create a customer usage limit', ['customer_id' => $request->get('id')]);

        try {
            /** @var Customer $customer */
            $customer = $customerRepository->getById($request->get('id'));
        } catch (NoEntityFoundException $exception) {
            return new JsonResponse(['success' => false], JsonResponse::HTTP_NOT_FOUND);
        }

        $createDto = $serializer->deserialize($request->getContent(), CreateUsageLimit::class, 'json');
        $errors = $validator->validate($createDto);
        $errorResponse = $this->handleErrors($errors);

        if ($errorResponse instanceof Response) {
            return $errorResponse;
        }

        $entity = $usageLimitDataMapper->createEntity($customer, $createDto);
        $usageLimitRepository->save($entity);

        return new Response(null, Response::HTTP_CREATED);
    }
}
