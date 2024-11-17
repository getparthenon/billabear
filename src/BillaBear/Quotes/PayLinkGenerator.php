<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Quotes;

use BillaBear\Entity\Quote;
use BillaBear\Repository\SettingsRepositoryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PayLinkGenerator implements PayLinkGeneratorInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private SettingsRepositoryInterface $settingsRepository,
    ) {
    }

    public function generatePayLink(Quote $quote): string
    {
        $payLink = $this->urlGenerator->generate('portal_pay_quote', ['hash' => $quote->getId()], UrlGeneratorInterface::ABSOLUTE_PATH);

        return $this->settingsRepository->getDefaultSettings()->getSystemSettings()->getSystemUrl().$payLink;
    }
}
