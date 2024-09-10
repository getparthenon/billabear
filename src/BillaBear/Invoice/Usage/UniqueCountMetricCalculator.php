<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Invoice\Usage;

use BillaBear\Entity\Subscription;
use BillaBear\Entity\Usage\Metric;
use BillaBear\Enum\MetricAggregationMethod;
use BillaBear\Repository\Usage\EventRepositoryInterface;

class UniqueCountMetricCalculator implements MetricCalculatorInterface
{
    public function __construct(private EventRepositoryInterface $eventRepository)
    {
    }

    public function supports(Metric $metric): bool
    {
        return MetricAggregationMethod::UNIQUE_COUNT === $metric->getAggregationMethod();
    }

    public function getMonthlyValue(Subscription $subscription): float
    {
        return $this->eventRepository->getUniqueCountForMonth($subscription->getCustomer(), $subscription->getPrice()->getMetric(), $subscription);
    }

    public function getYearlyValue(Subscription $subscription): float
    {
        return $this->eventRepository->getUniqueCountForYear($subscription->getCustomer(), $subscription->getPrice()->getMetric(), $subscription);
    }

    public function getWeeklyValue(Subscription $subscription): float
    {
        return $this->eventRepository->getUniqueCountForWeek($subscription->getCustomer(), $subscription->getPrice()->getMetric(), $subscription);
    }

    public function updatedAfter(Subscription $subscription, \DateTime $dateTime): bool
    {
        return $this->eventRepository->getEventCountAfterDateTime($subscription->getCustomer(), $subscription->getPrice()->getMetric(), $subscription, $dateTime) > 0;
    }
}
