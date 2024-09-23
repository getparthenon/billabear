<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Generic\Api\Usage;

use Symfony\Component\Serializer\Attribute\SerializedName;

class Metric
{
    private string $id;

    private string $name;

    private string $code;

    #[SerializedName('aggregation_method')]
    private string $aggregationMethod;

    #[SerializedName('aggregation_property')]
    private ?string $aggregationProperty;

    #[SerializedName('event_ingestion')]
    private string $eventIngestion;

    private array $filters;

    private \DateTime $createdAt;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getAggregationMethod(): string
    {
        return $this->aggregationMethod;
    }

    public function setAggregationMethod(string $aggregationMethod): void
    {
        $this->aggregationMethod = $aggregationMethod;
    }

    public function getAggregationProperty(): ?string
    {
        return $this->aggregationProperty;
    }

    public function setAggregationProperty(?string $aggregationProperty): void
    {
        $this->aggregationProperty = $aggregationProperty;
    }

    public function getEventIngestion(): string
    {
        return $this->eventIngestion;
    }

    public function setEventIngestion(string $eventIngestion): void
    {
        $this->eventIngestion = $eventIngestion;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): void
    {
        $this->filters = $filters;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
