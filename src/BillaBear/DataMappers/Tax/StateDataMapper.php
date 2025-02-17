<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\DataMappers\Tax;

use BillaBear\Dto\Generic\App\State as AppDto;
use BillaBear\Dto\Request\App\Country\CreateState;
use BillaBear\Dto\Request\App\Country\UpdateState;
use BillaBear\Entity\State as Entity;
use BillaBear\Repository\CountryRepositoryInterface;
use BillaBear\Tax\ThresholdType;

class StateDataMapper
{
    public function __construct(
        private CountryRepositoryInterface $countryRepository,
        private CountryDataMapper $dataMapper,
    ) {
    }

    public function createEntity(CreateState|UpdateState $createState, ?Entity $entity = null): Entity
    {
        if (!$entity) {
            $entity = new Entity();
        }

        $entity->setCollecting($createState->getCollecting());
        $entity->setName($createState->getName());
        $entity->setCode($createState->getCode());
        $entity->setThreshold($createState->getThreshold());
        if ($createState instanceof CreateState) {
            $entity->setCountry($this->countryRepository->findById($createState->getCountry()));
        }
        $entity->setTransactionThreshold($entity->getTransactionThreshold());
        $entity->setThresholdType(ThresholdType::from($createState->getThresholdType()));

        return $entity;
    }

    public function createAppDto(Entity $entity): AppDto
    {
        $dto = new AppDto();
        $dto->setId((string) $entity->getId());
        $dto->setName($entity->getName());
        $dto->setCode($entity->getCode());
        $dto->setCollecting($entity->isCollecting());
        $dto->setCountry($this->dataMapper->createAppDto($entity->getCountry()));
        $dto->setThreshold($entity->getThreshold());
        $dto->setTransactionThreshold($entity->getTransactionThreshold());
        $dto->setThresholdType($entity->getThresholdType());

        return $dto;
    }
}
