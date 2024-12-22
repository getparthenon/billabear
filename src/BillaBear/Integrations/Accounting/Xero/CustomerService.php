<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Integrations\Accounting\Xero;

use BillaBear\Entity\Customer;
use BillaBear\Exception\Integrations\UnexpectedErrorException;
use BillaBear\Integrations\Accounting\CustomerInterface;
use BillaBear\Integrations\Accounting\CustomerRegistration;
use GuzzleHttp\ClientInterface;
use Parthenon\Common\LoggerAwareTrait;
use XeroAPI\XeroPHP\Api\AccountingApi;
use XeroAPI\XeroPHP\Configuration;
use XeroAPI\XeroPHP\Models\Accounting\Address;
use XeroAPI\XeroPHP\Models\Accounting\Contact;
use XeroAPI\XeroPHP\Models\Accounting\Contacts;

class CustomerService implements CustomerInterface
{
    use LoggerAwareTrait;

    private AccountingApi $accountingApi;

    public function __construct(
        private string $tenantId,
        Configuration $config,
        ClientInterface $client,
    ) {
        $this->accountingApi = new AccountingApi($client, $config);
    }

    public function register(Customer $customer): CustomerRegistration
    {
        $this->getLogger()->info('Registering customer to xero', ['customer_id' => (string) $customer->getId()]);
        $contacts = $this->buildContacts($customer);

        try {
            $output = $this->accountingApi->createContacts($this->tenantId, $contacts);
        } catch (\Exception $e) {
            $this->logger->error('Failed to create contact to xero', ['exception_message' => $e->getMessage()]);
            throw new UnexpectedErrorException($e->getMessage(), previous: $e);
        }
        /** @var Contact $contactData */
        $contactData = $output->getContacts()[0];
        $id = $contactData->getContactId();

        $this->getLogger()->info('Customer registered to xero', ['customer_id' => (string) $customer->getId(), 'accounting_reference' => $id]);

        return new CustomerRegistration($id);
    }

    public function update(Customer $customer): void
    {
        $this->getLogger()->info('Updating customer in xero', ['customer_id' => (string) $customer->getId()]);
        $contacts = $this->buildContacts($customer);
        try {
            $this->accountingApi->updateContact($this->tenantId, $customer->getAccountingReference(), $contacts);
        } catch (\Exception $e) {
            $this->logger->error('Failed to update contact to xero', ['exception_message' => $e->getMessage()]);
            throw new UnexpectedErrorException($e->getMessage(), previous: $e);
        }
        $this->getLogger()->info('Customer updated in xero', ['customer_id' => (string) $customer->getId(), 'accounting_reference' => $id]);
    }

    public function delete(Customer $customer): void
    {
        $customer->setAccountingReference(null);
    }

    public function findCustomer(Customer $customer): ?CustomerRegistration
    {
        /** @var Contacts $contacts */
        $contacts = $this->accountingApi->getContacts($this->tenantId, search_term: $customer->getBillingEmail());
        if (1 === $contacts->getIterator()->count()) {
            /** @var Contact $contact */
            $contact = $contacts->getContacts()[0];

            return new CustomerRegistration($contact->getContactId());
        }

        return null;
    }

    public function buildContacts(Customer $customer): Contacts
    {
        $contact = new Contact();
        $contact->setName($customer->getName());
        $contact->setEmailAddress($customer->getBillingEmail());
        if ($customer->getAccountingReference()) {
            $contact->setContactId($customer->getAccountingReference());
        }

        $address = new Address();
        $address->setAddressLine1($customer->getBillingAddress()->getStreetLineOne());
        $address->setAddressLine2($customer->getBillingAddress()->getStreetLineTwo());
        $address->setCity($customer->getBillingAddress()->getCity());
        $address->setCountry($customer->getBillingAddress()->getCountry());
        $address->setPostalCode($customer->getBillingAddress()->getPostcode());

        $contact->setAddresses([$address]);

        $contacts = new Contacts();
        $contacts->setContacts([$contact]);

        return $contacts;
    }
}
