<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\DataMappers\Tax;

use BillaBear\Dto\Generic\App\Country as AppDto;
use BillaBear\Dto\Request\App\Country\CreateCountry;
use BillaBear\Dto\Request\App\Country\UpdateCountry;
use BillaBear\Entity\Country as Entity;
use BillaBear\Tax\ThresholdManager;
use BillaBear\Tax\ThresholdType;
use writecrow\CountryCodeConverter\CountryCodeConverter;

class CountryDataMapper
{
    public function __construct(private ThresholdManager $manager)
    {
    }

    public function createEntity(CreateCountry|UpdateCountry $updateCountry, ?Entity $entity = null): Entity
    {
        if (!$entity) {
            $entity = new Entity();
            $entity->setCreatedAt(new \DateTime('now'));
            $entity->setEnabled(true);
        }

        $entity->setName($updateCountry->getName());
        $entity->setIsoCode($updateCountry->getIsoCode());
        $entity->setCurrency($updateCountry->getCurrency());
        $entity->setThreshold($updateCountry->getThreshold());
        $entity->setInEu($updateCountry->getInEu() ?? false);
        $entity->setStartOfTaxYear($updateCountry->getStartOfTaxYear());
        $entity->setEnabled($updateCountry->isEnabled());
        $entity->setTaxNumber($updateCountry->getTaxNumber());
        $entity->setCollecting($updateCountry->getCollecting());
        $entity->setTransactionThreshold($updateCountry->getTransactionThreshold());
        $entity->setThresholdType(ThresholdType::from($updateCountry->getThresholdType()));

        return $entity;
    }

    public function createAppDto(Entity $entity): AppDto
    {
        $appDto = new AppDto();
        $appDto->setId($entity->getId());
        $appDto->setName($entity->getName());
        $appDto->setIsoCode($entity->getIsoCode());
        $appDto->setIsoCode3(CountryCodeConverter::convert($entity->getIsoCode(), 'three-digit'));
        $appDto->setCurrency($entity->getCurrency());
        $appDto->setThreshold($entity->getThreshold());
        $appDto->setInEu($entity->isInEu());
        $appDto->setStartOfTaxYear($entity->getStartOfTaxYear());
        $appDto->setEnabled($entity->isEnabled());
        $appDto->setTaxNumber($entity->getTaxNumber());
        $appDto->setTransactionThreshold($entity->getTransactionThreshold());
        $appDto->setThresholdType($entity->getThresholdType());

        $amountTransacted = $this->manager->getTransactedAmount($entity);
        $appDto->setAmountTransacted($amountTransacted->getMinorAmount()->toInt());
        if ($entity->getCollecting()) {
            $appDto->setCollecting($entity->getCollecting());
        } else {
            $appDto->setCollecting($appDto->getAmountTransacted() > $entity->getThreshold());
        }

        return $appDto;
    }
}
