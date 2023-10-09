<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: 09.10.2026 ( 3 years after 2023.4 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Dto\Generic\Public;

use Symfony\Component\Serializer\Annotation\SerializedName;

class SubscriptionPlan
{
    private $id;

    private string $name;

    #[SerializedName('code_name')]
    private ?string $codeName;

    #[SerializedName('user_count')]
    private int $userCount;

    #[SerializedName('per_seat')]
    private bool $perSeat;

    #[SerializedName('has_trial')]
    private ?bool $hasTrial;

    #[SerializedName('trial_length_days')]
    private ?int $trialLengthDays;

    private bool $free;

    private bool $public;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCodeName(): ?string
    {
        return $this->codeName;
    }

    public function setCodeName(?string $codeName): void
    {
        $this->codeName = $codeName;
    }

    public function isPerSeat(): bool
    {
        return $this->perSeat;
    }

    public function setPerSeat(bool $perSeat): void
    {
        $this->perSeat = $perSeat;
    }

    public function isFree(): bool
    {
        return $this->free;
    }

    public function setFree(bool $free): void
    {
        $this->free = $free;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    public function getUserCount(): int
    {
        return $this->userCount;
    }

    public function setUserCount(int $userCount): void
    {
        $this->userCount = $userCount;
    }

    public function isHasTrial(): ?bool
    {
        return $this->hasTrial;
    }

    public function setHasTrial(?bool $hasTrial): void
    {
        $this->hasTrial = $hasTrial;
    }

    public function getTrialLengthDays(): ?int
    {
        return $this->trialLengthDays;
    }

    public function setTrialLengthDays(?int $trialLengthDays): void
    {
        $this->trialLengthDays = $trialLengthDays;
    }
}
