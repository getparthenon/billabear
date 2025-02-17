<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Response\App\Developer\Webhook;

class ViewWebhookEvent
{
    protected WebhookEvent $event;

    protected array $responses;

    public function getEvent(): WebhookEvent
    {
        return $this->event;
    }

    public function setEvent(WebhookEvent $event): void
    {
        $this->event = $event;
    }

    public function getResponses(): array
    {
        return $this->responses;
    }

    public function setResponses(array $responses): void
    {
        $this->responses = $responses;
    }
}
