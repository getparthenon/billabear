<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Request\App\Checkout;

use BillaBear\Validator\Constraints\TaxTypeExists;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCheckoutItem
{
    #[Assert\NotBlank]
    private $description;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private $amount;

    #[Assert\Currency]
    private $currency;

    #[Assert\Type('bool')]
    #[SerializedName('include_tax')]
    private $includeTax;

    #[Assert\NotBlank]
    #[SerializedName('tax_type')]
    #[TaxTypeExists]
    private $taxType;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    public function getIncludeTax()
    {
        return $this->includeTax;
    }

    public function setIncludeTax($includeTax): void
    {
        $this->includeTax = $includeTax;
    }

    public function getTaxType()
    {
        return $this->taxType;
    }

    public function setTaxType($taxType): void
    {
        $this->taxType = $taxType;
    }
}
