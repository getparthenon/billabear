<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Integrations\Newsletter\EmailOctopus;

use BillaBear\Entity\Customer;
use BillaBear\Exception\Integrations\UnexpectedErrorException;
use BillaBear\Integrations\Newsletter\CustomerRegistration;
use BillaBear\Integrations\Newsletter\CustomerServiceInterface;
use GoranPopovic\EmailOctopus\Client;
use Parthenon\Common\LoggerAwareTrait;

class CustomerService implements CustomerServiceInterface
{
    use LoggerAwareTrait;

    public function __construct(
        private Client $client,
    ) {
    }

    public function register(string $listId, Customer $customer): CustomerRegistration
    {
        $this->getLogger()->info('Registering customer to EmailOctopus', ['customer_id' => (string) $customer->getId()]);

        try {
            $response = $this->client->lists()->createContact($listId,
                [
                    'email_address' => $customer->getBillingEmail(),
                    'status' => 'SUBSCRIBED',
                ]
            );
        } catch (\Exception $e) {
            $this->getLogger()->error('Failed to register customer to EmailOctopus', ['customer_id' => (string) $customer->getId(), 'list_id' => $listId, 'error' => $e->getMessage()]);

            throw new UnexpectedErrorException('Failed to register customer to EmailOctopus', previous: $e);
        }

        $this->getLogger()->info('Registered customer to EmailOctopus', ['customer_id' => (string) $customer->getId(), 'list_id' => $listId]);

        return new CustomerRegistration($response['id']);
    }

    public function update(string $listId, string $reference, bool $subscribe, Customer $customer): void
    {
        $this->getLogger()->info('Updating customer to EmailOctopus', ['customer_id' => (string) $customer->getId(), 'list_id' => $listId]);

        try {
            $this->client->lists()->updateContact($listId, $reference,
                [
                    'email_address' => $customer->getBillingEmail(),
                    'status' => $subscribe ? 'SUBSCRIBED' : 'UNSUBSCRIBED',
                ]
            );
        } catch (\Exception $e) {
            $this->getLogger()->error('Failed to update customer to EmailOctopus', ['customer_id' => (string) $customer->getId(), 'list_id' => $listId, 'error' => $e->getMessage()]);

            throw new UnexpectedErrorException('Failed to update customer to EmailOctopus', previous: $e);
        }
        $this->getLogger()->info('Updating customer to EmailOctopus', ['customer_id' => (string) $customer->getId(), 'list_id' => $listId]);
    }

    public function isSubscribed(string $listId, string $reference): bool
    {
        $this->getLogger()->info('Checking if customer is subscribed to EmailOctopus', ['list_id' => $listId, 'reference' => $reference]);

        $data = $this->client->lists()->getContact($listId, $reference);

        return 'SUBSCRIBED' === strtoupper($data['status']);
    }
}
