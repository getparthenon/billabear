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

namespace App\Api\Filters;

use Parthenon\Athena\Filters\ContainsFilter;

class InvoiceList extends AbstractFilterList
{
    protected function getFilters(): array
    {
        return [
            'email' => [
                'field' => 'customer.billingEmail',
                'filter' => ContainsFilter::class,
            ],
            'number' => [
                'field' => 'invoiceNumber',
                'filter' => ContainsFilter::class,
            ],
        ];
    }
}
