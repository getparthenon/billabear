<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Notification\Slack\Data\Tax;

use BillaBear\Entity\State;
use BillaBear\Notification\Slack\Data\AbstractNotification;
use BillaBear\Notification\Slack\SlackNotificationEvent;

class StateThresholdReached extends AbstractNotification
{
    public function __construct(private State $state)
    {
    }

    public function getEvent(): SlackNotificationEvent
    {
        return SlackNotificationEvent::TAX_COUNTRY_THRESHOLD_REACHED;
    }

    protected function getData(): array
    {
        return [
            'country' => [
                'name' => $this->state->getCountry()->getName(),
                'code' => $this->state->getCountry()->getIsoCode(),
                'threshold' => (string) $this->state->getCountry()->getThresholdAsMoney()->getAmount(),
            ],
            'state' => [
                'name' => $this->state->getName(),
                'threshold' => (string) $this->state->getThresholdAsMoney()->getAmount(),
            ],
        ];
    }
}
