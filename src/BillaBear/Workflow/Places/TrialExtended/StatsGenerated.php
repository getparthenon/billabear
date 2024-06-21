<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Workflow\Places\TrialExtended;

use BillaBear\Enum\WorkflowType;
use BillaBear\Workflow\Places\PlaceInterface;

class StatsGenerated implements PlaceInterface
{
    public function getName(): string
    {
        return 'stats_generated';
    }

    public function getPriority(): int
    {
        return 200;
    }

    public function getWorkflow(): WorkflowType
    {
        return WorkflowType::TRIAL_EXTENDED;
    }

    public function getToTransitionName(): string
    {
        return 'handle_stats';
    }

    public function isEnabled(): bool
    {
        return true;
    }
}
