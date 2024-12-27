<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Notification\Email\Data;

use BillaBear\Entity\BrandSettings;
use BillaBear\Entity\Customer;
use BillaBear\Entity\EmailTemplate;
use Parthenon\Billing\Entity\Subscription;

class DayBeforeChargeWarningEmail extends AbstractEmailData
{
    public function __construct(
        private Subscription $subscription,
    ) {
    }

    public function getData(Customer $customer, BrandSettings $brandSettings): array
    {
        return [
            'brand' => $this->getBrandData($brandSettings),
            'customer' => $this->getCustomerData($customer),
            'subscription' => $this->getSubscriptionData($this->subscription),
        ];
    }

    public function getTemplateName(): string
    {
        return EmailTemplate::NAME_SUBSCRIPTION_RENEWAL_WARNING;
    }

    protected function getSubscriptionData(Subscription $subscription): array
    {
        return [
            'plan_name' => $subscription->getPlanName(),
            'finishes_at' => $subscription->getValidUntil()->format(\DATE_ATOM),
        ];
    }
}
