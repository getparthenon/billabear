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

namespace App\Factory;

use App\Dto\Generic\Api\Price as ApiDto;
use App\Dto\Request\Api\CreatePrice;
use Parthenon\Billing\Entity\Price;

class PriceFactory
{
    public function createPriceFromDto(CreatePrice $createPrice, ?Price $price = null): Price
    {
        if (!$price) {
            $price = new Price();
        }

        $price->setAmount($createPrice->getAmount());
        $price->setCurrency($createPrice->getCurrency());

        if ($createPrice->hasExternalReference()) {
            $price->setExternalReference($createPrice->getExternalReference());
            $price->setPaymentProviderDetailsUrl(null);
        }

        $price->setPublic($createPrice->isPublic());
        $price->setRecurring($createPrice->isRecurring());
        $price->setSchedule($createPrice->getSchedule());
        $price->setIncludingTax($createPrice->isIncludingTax());

        return $price;
    }

    public function createApiDtoFromCustomer(Price $price): ApiDto
    {
        $dto = new ApiDto();
        $dto->setId((string) $price->getId());
        $dto->setExternalReference($price->getExternalReference());
        $dto->setAmount($price->getAmount());
        $dto->setCurrency($price->getCurrency());
        $dto->setRecurring($price->isRecurring());
        $dto->setSchedule($price->getSchedule());
        $dto->setPublic($price->isPublic());

        return $dto;
    }
}
