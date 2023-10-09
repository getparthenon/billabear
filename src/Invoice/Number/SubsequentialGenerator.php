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

namespace App\Invoice\Number;

use App\Repository\SettingsRepositoryInterface;

class SubsequentialGenerator implements InvoiceNumberGeneratorInterface
{
    public function __construct(private SettingsRepositoryInterface $settingsRepository)
    {
    }

    public function generate(): string
    {
        $settings = $this->settingsRepository->getDefaultSettings();
        $count = $settings->getSystemSettings()->getSubsequentialNumber();
        ++$count;
        $settings->getSystemSettings()->setSubsequentialNumber($count);
        $this->settingsRepository->save($settings);

        return strval($count);
    }
}
