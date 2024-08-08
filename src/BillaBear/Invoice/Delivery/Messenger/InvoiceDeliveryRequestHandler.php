<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Invoice\Delivery\Messenger;

use BillaBear\Entity\InvoiceDelivery;
use BillaBear\Enum\InvoiceDeliveryStatus;
use BillaBear\Invoice\Delivery\DeliveryHandlerProvider;
use BillaBear\Repository\InvoiceDeliveryRepositoryInterface;
use BillaBear\Repository\InvoiceDeliverySettingsRepositoryInterface;
use BillaBear\Repository\InvoiceRepositoryInterface;
use Parthenon\Common\LoggerAwareTrait;
use Parthenon\MultiTenancy\Database\DatabaseSwitcherInterface;
use Parthenon\MultiTenancy\TenantProvider\TenantProviderInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class InvoiceDeliveryRequestHandler
{
    use LoggerAwareTrait;

    public function __construct(
        private InvoiceRepositoryInterface $invoiceRepository,
        private InvoiceDeliverySettingsRepositoryInterface $invoiceDeliverySettingsRepository,
        private InvoiceDeliveryRepositoryInterface $invoiceDeliveryRepository,
        private DeliveryHandlerProvider $deliveryHandlerProvider,
        private DatabaseSwitcherInterface $databaseSwitcher,
        private TenantProviderInterface $tenantProvider,
    ) {
    }

    public function __invoke(InvoiceDeliveryRequest $invoiceDeliveryRequest): void
    {
        $this->databaseSwitcher->switchToTenant($this->tenantProvider->getCurrentTenant());

        $this->getLogger()->info(
            'Handling invoice delivery request',
            [
                'invoice_id' => $invoiceDeliveryRequest->invoiceId,
                'invoice_delivery_id' => $invoiceDeliveryRequest->invoiceDeliveryId,
            ]
        );

        $invoice = $this->invoiceRepository->findById($invoiceDeliveryRequest->invoiceId);
        $invoiceDeliverySettings = $this->invoiceDeliverySettingsRepository->findById($invoiceDeliveryRequest->invoiceDeliveryId);

        $handler = $this->deliveryHandlerProvider->getDeliveryHandler($invoiceDeliverySettings);
        try {
            $invoiceDelivery = new InvoiceDelivery();
            $invoiceDelivery->setInvoice($invoice);
            $invoiceDelivery->setInvoiceDeliverySettings($invoiceDeliverySettings);
            $invoiceDelivery->setCustomer($invoice->getCustomer());
            $invoiceDelivery->setCreatedAt(new \DateTime());
            $handler->deliver($invoice, $invoiceDeliverySettings);
            $invoiceDelivery->setStatus(InvoiceDeliveryStatus::SUCCESS);
        } catch (\Throwable $exception) {
            $invoiceDelivery->setStatus(InvoiceDeliveryStatus::FAILED);
        } finally {
            $this->invoiceDeliveryRepository->save($invoiceDelivery);
        }
    }
}
