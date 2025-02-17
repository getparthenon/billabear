<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Generic\App\Workflows;

use Symfony\Component\Serializer\Attribute\SerializedName;

class EditWorkflow
{
    #[SerializedName('handlers')]
    private array $transitionHandlers = [];

    private array $places = [];

    public function getTransitionHandlers(): array
    {
        return $this->transitionHandlers;
    }

    public function setTransitionHandlers(array $transitionHandlers): void
    {
        $this->transitionHandlers = $transitionHandlers;
    }

    public function getPlaces(): array
    {
        return $this->places;
    }

    public function setPlaces(array $places): void
    {
        $this->places = $places;
    }
}
