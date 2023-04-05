<?php

/*
 * Copyright Iain Cambridge 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: TBD ( 3 years after 1.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Dto\Generic\App;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Subscription
{
    private string $id;

    private string $schedule;

    private string $status;

    #[SerializedName('created_at')]
    private \DateTimeInterface $createdAt;

    #[SerializedName('updated_at')]
    private \DateTimeInterface $updatedAt;

    #[SerializedName('ended_at')]
    private ?\DateTimeInterface $endedAt = null;

    #[SerializedName('valid_until')]
    private \DateTimeInterface $validUntil;

    #[SerializedName('main_external_reference')]
    private string $externalMainReference;

    #[SerializedName('external_main_reference_details_url')]
    private ?string $externalMainReferenceDetailsUrl = null;

    #[SerializedName('child_external_reference')]
    private string $childExternalReference;

    #[SerializedName('plan')]
    private SubscriptionPlan $subscriptionPlan;

    private Price $price;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSchedule(): string
    {
        return $this->schedule;
    }

    public function setSchedule(string $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeInterface $endedAt): void
    {
        $this->endedAt = $endedAt;
    }

    public function getValidUntil(): \DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(\DateTimeInterface $validUntil): void
    {
        $this->validUntil = $validUntil;
    }

    public function getExternalMainReference(): string
    {
        return $this->externalMainReference;
    }

    public function setExternalMainReference(string $externalMainReference): void
    {
        $this->externalMainReference = $externalMainReference;
    }

    public function getExternalMainReferenceDetailsUrl(): ?string
    {
        return $this->externalMainReferenceDetailsUrl;
    }

    public function setExternalMainReferenceDetailsUrl(?string $externalMainReferenceDetailsUrl): void
    {
        $this->externalMainReferenceDetailsUrl = $externalMainReferenceDetailsUrl;
    }

    public function getChildExternalReference(): string
    {
        return $this->childExternalReference;
    }

    public function setChildExternalReference(string $childExternalReference): void
    {
        $this->childExternalReference = $childExternalReference;
    }

    public function getSubscriptionPlan(): SubscriptionPlan
    {
        return $this->subscriptionPlan;
    }

    public function setSubscriptionPlan(SubscriptionPlan $subscriptionPlan): void
    {
        $this->subscriptionPlan = $subscriptionPlan;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
