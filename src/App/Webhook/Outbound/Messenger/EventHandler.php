<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 */

namespace App\Webhook\Outbound\Messenger;

use App\Webhook\Outbound\EventProcessor;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class EventHandler
{
    public function __construct(private EventProcessor $eventProcessor)
    {
    }

    public function __invoke(EventMessage $eventMessage)
    {
        $this->eventProcessor->process($eventMessage);
    }
}
