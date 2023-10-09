<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: 09.10.2026 ( 3 years after 2023.4 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Repository;

use App\Entity\Customer;
use Parthenon\Athena\Repository\DoctrineCrudRepository;

class InvoiceRepository extends DoctrineCrudRepository implements InvoiceRepositoryInterface
{
    public function getAllForCustomer(Customer $customer): array
    {
        return $this->entityRepository->findBy(['customer' => $customer]);
    }

    public function getOverdueInvoices(): array
    {
        $qb = $this->entityRepository->createQueryBuilder('i');
        $qb->where('i.paid = false')
            ->andWhere('i.dueAt < :now')
            ->setParameter(':now', new \DateTime('now'));

        $query = $qb->getQuery();
        $query->execute();

        return $query->getResult();
    }
}
