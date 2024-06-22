<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Invoice\Number;

use BillaBear\Repository\SettingsRepositoryInterface;

class InvoiceNumberGeneratorProvider
{
    public function __construct(
        private SettingsRepositoryInterface $settingsRepository,
        private RandomInvoiceNumberGenerator $randomInvoiceNumberGenerator,
        private SubsequentialGenerator $subsequentialGenerator,
        private FormatNumberGenerator $formatNumberGenerator,
    ) {
    }

    public function getGenerator(): InvoiceNumberGeneratorInterface
    {
        $generation = $this->settingsRepository->getDefaultSettings()->getSystemSettings()->getInvoiceNumberGeneration();
        if ('subsequential' === $generation) {
            return $this->subsequentialGenerator;
        }

        if ('format' === $generation) {
            return $this->formatNumberGenerator;
        }

        return $this->randomInvoiceNumberGenerator;
    }
}
