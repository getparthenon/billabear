<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Integrations\Accounting\Action;

use BillaBear\Entity\Refund;
use BillaBear\Integrations\IntegrationManager;
use BillaBear\Repository\RefundRepositoryInterface;
use BillaBear\Repository\SettingsRepositoryInterface;

readonly class SyncRefund
{
    public function __construct(
        private RefundRepositoryInterface $refundRepository,
        private SettingsRepositoryInterface $settingsRepository,
        private IntegrationManager $integrationManager,
    ) {
    }

    public function sync(Refund $refund): void
    {
        $settings = $this->settingsRepository->getDefaultSettings();

        if (!$settings->getAccountingIntegration()->getEnabled()) {
            return;
        }
        $integration = $this->integrationManager->getAccountingIntegration($settings->getAccountingIntegration()->getIntegration());
        $customerService = $integration->getCreditService();
        if ($refund->getAccountingReference()) {
            return;
        }

        $registration = $customerService->registerRefund($refund);
        $refund->setAccountingReference($registration->refundReference);

        $this->refundRepository->save($refund);
    }
}
