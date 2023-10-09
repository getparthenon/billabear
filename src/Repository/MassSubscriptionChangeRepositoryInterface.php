<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: xx.10.2026 ( 3 years after 2023.4 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Repository;

use App\Entity\MassSubscriptionChange;
use Parthenon\Athena\Repository\CrudRepositoryInterface;

/**
 * @method \App\Entity\MassSubscriptionChange findById($id)
 */
interface MassSubscriptionChangeRepositoryInterface extends CrudRepositoryInterface
{
    /**
     * @return MassSubscriptionChange[]
     */
    public function findWithinFiveMinutes(\DateTime $dateTime): array;
}
