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

namespace App\Dto;

use App\Dto\Generic\Address;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCustomerDto
{
    #[Assert\NotBlank(allowNull: true)]
    #[SerializedName('name')]
    private $name = null;

    #[Assert\NotBlank(allowNull: true)]
    #[SerializedName('brand')]
    private $brand = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[SerializedName('email')]
    private $email;

    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Locale()]
    #[SerializedName('brand')]
    private $locale;

    #[SerializedName('reference')]
    private $reference = null;

    #[SerializedName('external_reference')]
    private $externalReference = null;

    #[SerializedName('billing_type')]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Type('string')]
    #[Assert\Choice(choices: ['invoice', 'card'])]
    private $billingType = null;

    #[Assert\Valid]
    #[SerializedName('address')]
    private Address $address;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): void
    {
        $this->reference = $reference;
    }

    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    public function setExternalReference(?string $externalReference): void
    {
        $this->externalReference = $externalReference;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

    public function getBillingType()
    {
        return $this->billingType;
    }

    public function setBillingType($billingType): void
    {
        $this->billingType = $billingType;
    }
}
