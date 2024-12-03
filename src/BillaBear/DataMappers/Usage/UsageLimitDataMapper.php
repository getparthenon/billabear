<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\DataMappers\Usage;

use BillaBear\Dto\Request\App\Usage\CreateUsageLimit;
use BillaBear\Entity\Customer;
use BillaBear\Entity\UsageLimit as Entity;
use BillaBear\Enum\WarningLevel;

class UsageLimitDataMapper
{
    public function createEntity(Customer $customer, CreateUsageLimit $createUsageLimit): Entity
    {
        $entity = new Entity();
        $entity->setCustomer($customer);
        $entity->setAmount($createUsageLimit->getAmount());
        $entity->setWarningLevel(WarningLevel::from($createUsageLimit->getWarnLevel()));

        return $entity;
    }
}
