<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Customer\Messenger;

use BillaBear\Integrations\Accounting\Action\SyncCustomer as AccountingSyncCustomer;
use BillaBear\Integrations\CustomerSupport\Action\SyncCustomer as CustomerSupportSyncCustomer;
use BillaBear\Integrations\Newsletter\Action\SyncCustomer as NewsletterSyncCustomer;
use BillaBear\Repository\CustomerRepositoryInterface;
use Parthenon\Common\LoggerAwareTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CustomerEventHandler
{
    use LoggerAwareTrait;

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private AccountingSyncCustomer $accountingSyncCustomer,
        private CustomerSupportSyncCustomer $customerSupportSyncCustomer,
        private NewsletterSyncCustomer $newsletterSyncCustomer,
    ) {
    }

    public function __invoke(CustomerEvent $event)
    {
        $this->logger->info('Handling customer event', [
            'type' => $event->type->value,
            'customer_id' => $event->customerId,
        ]);

        $customer = $this->customerRepository->findById($event->customerId);
        $this->accountingSyncCustomer->sync($customer);
        $this->customerSupportSyncCustomer->sync($customer);
        $this->newsletterSyncCustomer->sync($customer);
    }
}
