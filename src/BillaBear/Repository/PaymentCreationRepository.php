<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Repository;

use Parthenon\Athena\Repository\DoctrineCrudRepository;

class PaymentCreationRepository extends DoctrineCrudRepository implements PaymentCreationRepositoryInterface
{
    public function getFailedProcesses(): \Generator
    {
        $queryBuilder = $this->entityRepository->createQueryBuilder('pc');
        $queryBuilder->select('pc');
        $queryBuilder->where('pc.hasError = true');
        $query = $queryBuilder->getQuery();

        $query->execute();
        foreach ($query->toIterable() as $result) {
            yield $result;
        }
    }
}
