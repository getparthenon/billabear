<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Repository\Usage;

use BillaBear\Entity\UsageLimit;
use Parthenon\Common\Repository\RepositoryInterface;

interface UsageWarningRepositoryInterface extends RepositoryInterface
{
    public function hasOneForUsageLimitAndDates(UsageLimit $usageLimit, \DateTime $startOfPeriod, \DateTime $endOfPeriod): bool;
}
