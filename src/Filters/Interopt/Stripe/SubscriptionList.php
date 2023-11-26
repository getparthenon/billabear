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

namespace App\Filters\Interopt\Stripe;

use App\Filters\AbstractFilterList;
use Parthenon\Athena\Filters\ExactChoiceFilter;

class SubscriptionList extends AbstractFilterList
{
    protected function getFilters(): array
    {
        return [
            'customer' => [
                'field' => 'customer',
                'filter' => ExactChoiceFilter::class,
            ],
            'price' => [
                'field' => 'price',
                'filter' => ExactChoiceFilter::class,
            ],
        ];
    }
}
