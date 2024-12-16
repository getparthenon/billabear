<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Customer\Messenger;

use BillaBear\Repository\CustomerRepositoryInterface;
use Parthenon\Common\LoggerAwareTrait;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CustomerEventHandler
{
    use LoggerAwareTrait;

    public function __construct(private CustomerRepositoryInterface $customerRepository)
    {
    }

    public function __invoke(CustomerEvent $event)
    {
        $this->logger->info('Handling customer event', [
            'type' => $event->type->value,
            'customer_id' => $event->customerId,
        ]);

        $customer = $this->customerRepository->findById($event->customerId);

        // TODO add integration calls
    }
}
