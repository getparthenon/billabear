<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Controller\App\Invoice;

use BillaBear\Background\Generic\GenericTasks;
use BillaBear\Controller\ValidationErrorResponseTrait;
use BillaBear\DataMappers\Invoice\InvoiceDeliveryDataMapper;
use BillaBear\Dto\Request\App\Invoice\CreateInvoiceDelivery;
use BillaBear\Repository\CustomerRepositoryInterface;
use BillaBear\Repository\InvoiceDeliveryRepositoryInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Parthenon\Common\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InvoiceDeliveryController
{
    use LoggerAwareTrait;
    use ValidationErrorResponseTrait;

    public function __construct(private readonly GenericTasks $genericTasks)
    {
    }

    #[Route('/app/customer/{customerId}/invoice-delivery', name: 'invoice_delivery', methods: ['POST'])]
    public function createNew(
        Request $request,
        CustomerRepositoryInterface $customerRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        InvoiceDeliveryRepositoryInterface $invoiceDeliveryRepository,
        InvoiceDeliveryDataMapper $dataMapper,
    ): Response {
        $this->getLogger()->info('Received a request to create a new invoice_delivery');

        try {
            $customer = $customerRepository->getById($request->get('customerId'));
        } catch (NoEntityFoundException) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
        $createDto = $serializer->deserialize($request->getContent(), CreateInvoiceDelivery::class, 'json');
        $errors = $validator->validate($createDto);
        $response = $this->handleErrors($errors);

        if ($response instanceof Response) {
            return $response;
        }

        $invoiceDelivery = $dataMapper->createEntity($createDto);
        $invoiceDelivery->setCustomer($customer);
        $invoiceDeliveryRepository->save($invoiceDelivery);

        $appDto = $dataMapper->createAppDto($invoiceDelivery);
        $json = $serializer->serialize($appDto, 'json');

        return new JsonResponse($json, JsonResponse::HTTP_ACCEPTED);
    }
}
