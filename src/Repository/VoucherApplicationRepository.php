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
use App\Entity\VoucherApplication;
use Parthenon\Common\Exception\NoEntityFoundException;
use Parthenon\Common\Repository\DoctrineRepository;

class VoucherApplicationRepository extends DoctrineRepository implements VoucherApplicationRepositoryInterface
{
    public function findUnUsedForCustomer(Customer $customer): VoucherApplication
    {
        $voucherApplication = $this->entityRepository->findOneBy(['customer' => $customer, 'used' => false]);

        if (!$voucherApplication instanceof VoucherApplication) {
            throw new NoEntityFoundException(sprintf("No voucher application for '%s'", $customer->getBillingEmail()));
        }

        return $voucherApplication;
    }
}
