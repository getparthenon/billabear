<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Background\Notifications;

use BillaBear\Entity\SubscriptionPlan;
use BillaBear\Notification\Email\Data\DayBeforeChargeWarningEmail;
use BillaBear\Notification\Email\EmailBuilder;
use BillaBear\Repository\BrandSettingsRepositoryInterface;
use BillaBear\Repository\SubscriptionRepositoryInterface;
use Parthenon\Billing\Enum\SubscriptionStatus;
use Parthenon\Common\LoggerAwareTrait;
use Parthenon\Notification\EmailSenderInterface;
use Parthenon\Notification\Exception\UnableToSendMessageException;

class DayBeforeChargeWarning
{
    use LoggerAwareTrait;

    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptionRepository,
        private readonly BrandSettingsRepositoryInterface $brandSettingsRepository,
        private readonly EmailSenderInterface $sender,
        private readonly EmailBuilder $emailBuilder,
    ) {
    }

    public function execute(): void
    {
        $all = $this->brandSettingsRepository->getAll();
        $enabled = false;
        foreach ($all as $setting) {
            if ($setting->getNotificationSettings()->getSendBeforeChargeWarnings()) {
                $enabled = true;
            }
        }

        if (!$enabled) {
            return;
        }

        $this->getLogger()->info('Starting day before charge warning run');

        $subscriptions = $this->subscriptionRepository->getSubscriptionsExpiringInTwoDays();

        foreach ($subscriptions as $subscription) {
            if (!$subscription->getCustomer()->getBrandSettings()->getNotificationSettings()->getSendBeforeChargeWarnings()) {
                continue;
            }
            /** @var SubscriptionPlan $plan */
            $plan = $subscription->getSubscriptionPlan();
            if ($plan->getIsTrialStandalone() && SubscriptionStatus::TRIAL_ACTIVE === $subscription->getStatus()) {
                // There isn't going to be a next charge.
                continue;
            }

            $this->getLogger()->info(
                'Sending before charge warning email to customer for subscription',
                [
                    'customer_id' => (string) $subscription->getCustomer()->getId(),
                    'subscription_id' => (string) $subscription->getId(),
                ]
            );
            $emailPayload = new DayBeforeChargeWarningEmail($subscription);
            $email = $this->emailBuilder->build($subscription->getCustomer(), $emailPayload);
            try {
                $this->sender->send($email);
            } catch (UnableToSendMessageException $e) {
                $this->getLogger()->error('Failed to send day before charge warning email', ['exception_message' => $e->getMessage()]);
                // We'll continue on and just squash this in case it's related to just this email,
            }
        }
    }
}
