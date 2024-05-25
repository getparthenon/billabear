<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Repository;

use BillaBear\Entity\Country;
use BillaBear\Entity\CountryTaxRule;
use BillaBear\Entity\TaxType;
use Parthenon\Athena\Repository\CrudRepositoryInterface;

interface CountryTaxRuleRepositoryInterface extends CrudRepositoryInterface
{
    /**
     * @return CountryTaxRule[]
     */
    public function getForCountry(Country $country): array;

    public function getOpenEndedForCountryAndTaxType(Country $country, TaxType $taxType): CountryTaxRule;

    /**
     * @return CountryTaxRule[]
     */
    public function getForCountryAndTaxType(Country $country, TaxType $taxType): array;

    public function getDefaultForCountryAndTaxType(Country $country): ?CountryTaxRule;
}
