<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Generic\App;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Product
{
    #[SerializedName('id')]
    protected string $id;

    #[SerializedName('name')]
    protected string $name;

    #[SerializedName('tax_type')]
    protected ?TaxType $taxType;

    #[SerializedName('external_reference')]
    protected ?string $externalReference = null;

    #[SerializedName('payment_provider_details_url')]
    protected ?string $paymentProviderDetailsUrl = null;

    #[SerializedName('tax_rate')]
    protected ?float $taxRate = null;

    protected ?bool $physical;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getExternalReference(): ?string
    {
        return $this->externalReference;
    }

    public function setExternalReference(?string $externalReference): void
    {
        $this->externalReference = $externalReference;
    }

    public function getPaymentProviderDetailsUrl(): ?string
    {
        return $this->paymentProviderDetailsUrl;
    }

    public function setPaymentProviderDetailsUrl(?string $paymentProviderDetailsUrl): void
    {
        $this->paymentProviderDetailsUrl = $paymentProviderDetailsUrl;
    }

    public function getTaxType(): ?TaxType
    {
        return $this->taxType;
    }

    public function setTaxType(?TaxType $taxType): void
    {
        $this->taxType = $taxType;
    }

    public function getTaxRate(): ?float
    {
        return $this->taxRate;
    }

    public function setTaxRate(?float $taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getPhysical(): ?bool
    {
        return $this->physical;
    }

    public function setPhysical(?bool $physical): void
    {
        $this->physical = $physical;
    }
}
