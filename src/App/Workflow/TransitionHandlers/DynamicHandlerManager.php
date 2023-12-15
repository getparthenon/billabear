<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: DD.MM.2027 ( 3 years after 2024.1 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Workflow\TransitionHandlers;

class DynamicHandlerManager
{
    /**
     * @var DynamicHandlerInterface[]
     */
    private array $handlers = [];

    public function addHandler(DynamicHandlerInterface $dynamicHandler)
    {
        $this->handlers[] = $dynamicHandler;
    }

    public function getHandlerByName(string $name): DynamicHandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->getName() === $name) {
                return $handler;
            }
        }
        throw new \Exception(sprintf("Can't find handler for %s", $name));
    }
}
