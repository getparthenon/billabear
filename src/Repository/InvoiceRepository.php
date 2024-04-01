<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
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
