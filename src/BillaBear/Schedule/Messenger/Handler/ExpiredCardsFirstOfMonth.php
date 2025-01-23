<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Schedule\Messenger\Handler;

use BillaBear\Background\ExpiringCards\StartProcess;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ExpiredCardsFirstOfMonth
{
    public function __construct(private StartProcess $process)
    {
    }

    public function __invoke(\BillaBear\Schedule\Messenger\Message\ExpiredCardsFirstOfMonth $cardsFirstOfMonth)
    {
        $this->process->execute();
    }
}
