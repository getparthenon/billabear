<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Request\App\Country;

use BillaBear\Validator\Constraints\Country\UniqueCountryCode;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCountry
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private $name;

    #[SerializedName('iso_code')]
    #[Assert\NotBlank]
    #[Assert\Country]
    #[UniqueCountryCode]
    private $isoCode;

    #[Assert\NotBlank]
    #[Assert\Currency]
    private $currency;

    #[Assert\Type('integer')]
    #[Assert\PositiveOrZero]
    private $threshold;

    #[Assert\Type('boolean')]
    private $default;

    #[SerializedName('in_eu')]
    #[Assert\Type('boolean')]
    private $inEu = false;

    #[SerializedName('start_of_tax_year')]
    private $startOfTaxYear;

    #[Assert\Type('boolean')]
    private $enabled = true;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getIsoCode()
    {
        return $this->isoCode;
    }

    public function setIsoCode($isoCode): void
    {
        $this->isoCode = $isoCode;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    public function getThreshold()
    {
        return $this->threshold;
    }

    public function setThreshold($threshold): void
    {
        $this->threshold = $threshold;
    }

    public function getInEu()
    {
        return true === $this->inEu;
    }

    public function setInEu($inEu): void
    {
        $this->inEu = $inEu;
    }

    public function getDefault()
    {
        return true === $this->default;
    }

    public function setDefault($default): void
    {
        $this->default = $default;
    }

    public function getStartOfTaxYear()
    {
        return $this->startOfTaxYear;
    }

    public function setStartOfTaxYear($startOfTaxYear): void
    {
        $this->startOfTaxYear = $startOfTaxYear;
    }

    public function isEnabled(): bool
    {
        return true === $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
