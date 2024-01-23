<?php

/*
 * Copyright Humbly Arrogant Software Limited 2022-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: DD.MM.2027 ( 3 years after 2024.1 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Dto\Request\App\Country;

use App\Validator\Constraints\Country\CountryExists;
use App\Validator\Constraints\CountryTaxRule\DoesNotOverlap;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[DoesNotOverlap]
class UpdateCountryTaxRule
{
    #[Assert\NotBlank()]
    private $id;

    #[Assert\NotBlank()]
    #[CountryExists]
    private $country;

    #[SerializedName('tax_type')]
    #[Assert\NotBlank()]
    private $taxType;

    #[SerializedName('tax_rate')]
    #[Assert\NotBlank()]
    #[Assert\Type(['float', 'integer'])]
    private $taxRate;

    #[Assert\NotBlank()]
    #[Assert\Type('boolean')]
    private $default;

    #[SerializedName('valid_from')]
    #[Assert\NotBlank()]
    #[Assert\DateTime(format: \DATE_RFC3339_EXTENDED)]
    private $validFrom;

    #[SerializedName('valid_until')]
    #[Assert\DateTime(format: \DATE_RFC3339_EXTENDED)]
    private $validUntil;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getTaxType()
    {
        return $this->taxType;
    }

    public function setTaxType($taxType): void
    {
        $this->taxType = $taxType;
    }

    public function getTaxRate()
    {
        return $this->taxRate;
    }

    public function setTaxRate($taxRate): void
    {
        $this->taxRate = $taxRate;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function setDefault($default): void
    {
        $this->default = $default;
    }

    public function getValidFrom()
    {
        return $this->validFrom;
    }

    public function setValidFrom($validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    public function getValidUntil()
    {
        return $this->validUntil;
    }

    public function setValidUntil($validUntil): void
    {
        $this->validUntil = $validUntil;
    }
}
