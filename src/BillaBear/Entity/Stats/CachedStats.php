<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Entity\Stats;

use BillaBear\Stats\CachedStatsType;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

#[ORM\Entity]
#[ORM\Table('stats_cached_stats')]
class CachedStats
{
    public const STAT_NAME_ESTIMATED_MRR = 'estimated_mrr';
    public const STAT_NAME_ESTIMATED_ARR = 'estimated_arr';

    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Id]
    private $id;

    #[ORM\Column(type: 'string', enumType: CachedStatsType::class)]
    private CachedStatsType $type;

    #[ORM\Column(type: 'string', unique: true)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private int $value;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $currency;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getType(): CachedStatsType
    {
        return $this->type;
    }

    public function setType(CachedStatsType $type): void
    {
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }
}
