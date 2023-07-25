<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: DD.MM.2026 ( 3 years after 1.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Controller\App;

use App\Api\Filters\InvoiceList;
use App\Controller\ValidationErrorResponseTrait;
use App\DataMappers\InvoiceDataMapper;
use App\Dto\Request\App\Invoice\CreateInvoice;
use App\Dto\Response\App\ListResponse;
use App\Entity\Invoice;
use App\Invoice\ManualInvoiceCreator;
use App\Payment\InvoiceCharger;
use App\Pdf\InvoicePdfGenerator;
use App\Repository\InvoiceRepositoryInterface;
use Parthenon\Athena\Filters\BoolFilter;
use Parthenon\Common\Exception\NoEntityFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InvoicesController
{
    use ValidationErrorResponseTrait;

    #[Route('/app/invoices', name: 'app_invoices_list', methods: ['GET'])]
    public function listInvoice(
        Request $request,
        InvoiceRepositoryInterface $repository,
        SerializerInterface $serializer,
        InvoiceDataMapper $factory,
    ): Response {
        $lastKey = $request->get('last_key');
        $firstKey = $request->get('first_key');
        $resultsPerPage = (int) $request->get('per_page', 10);

        if ($resultsPerPage < 1) {
            return new JsonResponse([
                'success' => false,
                'reason' => 'per_page is below 1',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($resultsPerPage > 100) {
            return new JsonResponse([
                'success' => false,
                'reason' => 'per_page is above 100',
            ], JsonResponse::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }

        $filterBuilder = new InvoiceList();
        $filters = $filterBuilder->buildFilters($request);

        $resultSet = $repository->getList(
            filters: $filters,
            limit: $resultsPerPage,
            lastId: $lastKey,
            firstId: $firstKey,
        );

        $dtos = array_map([$factory, 'createAppDto'], $resultSet->getResults());
        $listResponse = new ListResponse();
        $listResponse->setHasMore($resultSet->hasMore());
        $listResponse->setData($dtos);
        $listResponse->setLastKey($resultSet->getLastKey());
        $listResponse->setFirstKey($resultSet->getFirstKey());

        $json = $serializer->serialize($listResponse, 'json');

        return new JsonResponse($json, json: true);
    }

    #[Route('/app/invoices/unpaid', name: 'app_invoices_unpaid_list', methods: ['GET'])]
    public function listUnpaidInvoice(
        Request $request,
        InvoiceRepositoryInterface $repository,
        SerializerInterface $serializer,
        InvoiceDataMapper $factory,
    ): Response {
        $lastKey = $request->get('last_key');
        $firstKey = $request->get('first_key');
        $resultsPerPage = (int) $request->get('per_page', 10);

        if ($resultsPerPage < 1) {
            return new JsonResponse([
                'success' => false,
                'reason' => 'per_page is below 1',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if ($resultsPerPage > 100) {
            return new JsonResponse([
                'success' => false,
                'reason' => 'per_page is above 100',
            ], JsonResponse::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }

        $filterBuilder = new InvoiceList();
        $filters = $filterBuilder->buildFilters($request);

        // Default filter since it's list unpaid
        $filter = new BoolFilter();
        $filter->setFieldName('paid');
        $filter->setData('false');
        $filters[] = $filter;

        $resultSet = $repository->getList(
            filters: $filters,
            limit: $resultsPerPage,
            lastId: $lastKey,
            firstId: $firstKey,
        );

        $dtos = array_map([$factory, 'createAppDto'], $resultSet->getResults());
        $listResponse = new ListResponse();
        $listResponse->setHasMore($resultSet->hasMore());
        $listResponse->setData($dtos);
        $listResponse->setLastKey($resultSet->getLastKey());
        $listResponse->setFirstKey($resultSet->getFirstKey());

        $json = $serializer->serialize($listResponse, 'json');

        return new JsonResponse($json, json: true);
    }

    #[Route('/app/invoice/{id}/download', name: 'app_invoice_download', methods: ['GET'])]
    public function downloadInvoice(
        Request $request,
        InvoiceRepositoryInterface $invoiceRepository,
        InvoicePdfGenerator $generator,
    ): Response {
        try {
            /** @var Invoice $invoice */
            $invoice = $invoiceRepository->getById($request->get('id'));
        } catch (NoEntityFoundException $exception) {
            return new JsonResponse([], status: JsonResponse::HTTP_NOT_FOUND);
        }
        $pdf = $generator->generate($invoice);
        $tmpFile = tempnam('/tmp', 'pdf');
        file_put_contents($tmpFile, $pdf);

        $response = new BinaryFileResponse($tmpFile);
        $filename = sprintf('invoice-%s.pdf', $invoice->getInvoiceNumber());

        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        return $response;
    }

    #[IsGranted('ROLE_CUSTOMER_SUPPORT')]
    #[Route('/app/invoice/{id}/charge', name: 'app_invoice_charge', methods: ['POST'])]
    public function chargeInvoice(
        Request $request,
        InvoiceRepositoryInterface $invoiceRepository,
        InvoiceCharger $invoiceCharger
    ): Response {
        try {
            /** @var Invoice $invoice */
            $invoice = $invoiceRepository->getById($request->get('id'));
        } catch (NoEntityFoundException $exception) {
            return new JsonResponse([], status: JsonResponse::HTTP_NOT_FOUND);
        }

        $invoiceCharger->chargeInvoice($invoice);

        return new JsonResponse(['paid' => $invoice->isPaid()]);
    }

    #[IsGranted('ROLE_ACCOUNT_MANAGER')]
    #[Route('/app/invoices/create', name: 'app_invoice_create', methods: ['POST'])]
    public function createInvoice(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        InvoiceDataMapper $invoiceFactory,
        ManualInvoiceCreator $manualInvoiceCreator,
    ): Response {
        /** @var CreateInvoice $dto */
        $dto = $serializer->deserialize($request->getContent(), CreateInvoice::class, 'json');
        $errors = $validator->validate($dto);
        $response = $this->handleErrors($errors);

        if ($response) {
            return $response;
        }

        $invoice = $manualInvoiceCreator->createInvoice($dto);
        $output = $invoiceFactory->createAppDto($invoice);
        $json = $serializer->serialize($output, 'json');

        return new JsonResponse($json, json: true);
    }
}
