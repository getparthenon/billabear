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

namespace App\Repository;

use App\Entity\Voucher;
use App\Enum\VoucherEvent;
use Parthenon\Athena\Repository\CrudRepositoryInterface;

interface VoucherRepositoryInterface extends CrudRepositoryInterface
{
    public function getActiveByCode(string $code): Voucher;

    public function getActiveByEvent(VoucherEvent $event): Voucher;
}
