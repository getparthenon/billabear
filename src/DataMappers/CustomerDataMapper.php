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

namespace App\DataMappers;

use App\Dto\Generic\Address as AddressDto;
use App\Dto\Generic\Api\Customer as CustomerApiDto;
use App\Dto\Generic\App\Customer as CustomerAppDto;
use App\Dto\Request\Api\CreateCustomerDto as ApiCreate;
use App\Dto\Request\App\CreateCustomerDto as AppCreate;
use App\Entity\Customer;
use App\Enum\CustomerStatus;
use App\Enum\CustomerType;
use App\Repository\BrandSettingsRepositoryInterface;
use Obol\Model\Customer as ObolCustomer;
use Parthenon\Common\Address;

class CustomerDataMapper
{
    public function __construct(private BrandSettingsRepositoryInterface $brandSettingRepository)
    {
    }

    public function createCustomerFromObol(ObolCustomer $obolCustomer, Customer $customer = null): Customer
    {
        if (!$customer) {
            $customer = new Customer();
            $customer->setStatus(CustomerStatus::NEW);
        }

        $customer->setBillingEmail($obolCustomer->getEmail());
        $customer->setName($obolCustomer->getName());
        $customer->setLocale(Customer::DEFAULT_LOCALE);
        $customer->setExternalCustomerReference($obolCustomer->getId());
        $customer->setPaymentProviderDetailsUrl($obolCustomer->getUrl());
        $customer->setReference($obolCustomer->getDescription());
        $customer->setCreatedAt($obolCustomer->getCreatedAt());

        $address = new Address();
        $address->setStreetLineTwo($obolCustomer->getAddress()->getStreetLineOne());
        $address->setStreetLineTwo($obolCustomer->getAddress()->getStreetLineTwo());
        $address->setCity($obolCustomer->getAddress()->getCity());
        $address->setRegion($obolCustomer->getAddress()->getState());
        $address->setPostcode($obolCustomer->getAddress()->getPostalCode());
        $address->setCountry($obolCustomer->getAddress()->getCountryCode());

        $customer->setBillingAddress($address);

        return $customer;
    }

    public function createCustomer(ApiCreate|AppCreate $createCustomerDto, Customer $customer = null): Customer
    {
        $address = new Address();
        $address->setStreetLineOne($createCustomerDto->getAddress()?->getStreetLineOne());
        $address->setStreetLineTwo($createCustomerDto->getAddress()?->getStreetLineTwo());
        $address->setCountry($createCustomerDto->getAddress()?->getCountry());
        $address->setCity($createCustomerDto->getAddress()?->getCity());
        $address->setRegion($createCustomerDto->getAddress()?->getRegion());
        $address->setPostcode($createCustomerDto->getAddress()?->getPostcode());

        if (!$customer) {
            $customer = new Customer();
            $customer->setStatus(CustomerStatus::NEW);
            $customer->setCreatedAt(new \DateTime('now'));
        }
        $customer->setType(CustomerType::INDIVIDUAL);
        $customer->setBillingEmail($createCustomerDto->getEmail());
        $customer->setReference($createCustomerDto->getReference());
        $customer->setBillingAddress($address);
        $customer->setName($createCustomerDto->getName());
        $customer->setBrand($createCustomerDto->getBrand() ?? Customer::DEFAULT_BRAND);
        $customer->setLocale($createCustomerDto->getLocale() ?? Customer::DEFAULT_LOCALE);
        $customer->setBillingType($createCustomerDto->getBillingType() ?? Customer::DEFAULT_BILLING_TYPE);
        $customer->setTaxNumber($createCustomerDto->getTaxNumber());
        $customer->setDigitalTaxRate($createCustomerDto->getDigitalTaxRate());
        $customer->setStandardTaxRate($createCustomerDto->getStandardTaxRate());

        $brandSettings = $this->brandSettingRepository->getByCode($customer->getBrand());
        $customer->setBrandSettings($brandSettings);

        $externalCustomerReference = $createCustomerDto->getExternalReference();

        if (isset($externalCustomerReference)) {
            $customer->setExternalCustomerReference($externalCustomerReference);
            $customer->setPaymentProviderDetailsUrl(null);
        }

        return $customer;
    }

    public function createApiDto(Customer $customer): CustomerApiDto
    {
        $address = new AddressDto();
        $address->setStreetLineOne($customer->getBillingAddress()->getStreetLineOne());
        $address->setStreetLineTwo($customer->getBillingAddress()->getStreetLineTwo());
        $address->setCity($customer->getBillingAddress()->getCity());
        $address->setRegion($customer->getBillingAddress()->getRegion());
        $address->setCountry($customer->getBillingAddress()->getCountry());
        $address->setPostcode($customer->getBillingAddress()->getPostcode());

        $dto = new CustomerApiDto();
        $dto->setName($customer->getName());
        $dto->setId((string) $customer->getId());
        $dto->setReference($customer->getReference());
        $dto->setEmail($customer->getBillingEmail());
        $dto->setExternalReference($customer->getExternalCustomerReference());
        $dto->setAddress($address);
        $dto->setStatus($customer->getStatus()->value);
        $dto->setBrand($customer->getBrand());
        $dto->setLocale($customer->getLocale());
        $dto->setBillingType($customer->getBillingType());
        $dto->setTaxNumber($customer->getTaxNumber());
        $dto->setDigitalTaxRate($customer->getDigitalTaxRate());
        $dto->setStandardTaxRate($customer->getStandardTaxRate());

        return $dto;
    }

    public function createAppDto(Customer $customer): CustomerAppDto
    {
        $address = new AddressDto();
        $address->setStreetLineOne($customer->getBillingAddress()->getStreetLineOne());
        $address->setStreetLineTwo($customer->getBillingAddress()->getStreetLineTwo());
        $address->setCity($customer->getBillingAddress()->getCity());
        $address->setRegion($customer->getBillingAddress()->getRegion());
        $address->setCountry($customer->getBillingAddress()->getCountry());
        $address->setPostcode($customer->getBillingAddress()->getPostcode());

        $dto = new CustomerAppDto();
        $dto->setName($customer->getName());
        $dto->setId((string) $customer->getId());
        $dto->setReference($customer->getReference());
        $dto->setEmail($customer->getBillingEmail());
        $dto->setExternalReference($customer->getExternalCustomerReference());
        $dto->setAddress($address);
        $dto->setPaymentProviderDetailsUrl($customer->getPaymentProviderDetailsUrl());
        $dto->setStatus($customer->getStatus()->value);
        $dto->setBrand($customer->getBrand());
        $dto->setLocale($customer->getLocale());
        $dto->setBillingType($customer->getBillingType());
        $dto->setTaxNumber($customer->getTaxNumber());
        $dto->setDigitalTaxRate($customer->getDigitalTaxRate());
        $dto->setStandardTaxRate($customer->getStandardTaxRate());

        return $dto;
    }
}
