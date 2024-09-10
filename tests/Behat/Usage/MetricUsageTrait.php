<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Tests\Behat\Usage;

use BillaBear\Entity\Usage\MetricUsage;

trait MetricUsageTrait
{
    private function getMetricUsage($customerEmail, $metricName)
    {
        $customer = $this->getCustomerByEmail($customerEmail);
        $metric = $this->getMetric($metricName);

        $usage = $this->metricUsageRepository->findOneBy(['customer' => $customer, 'metric' => $metric]);

        if (!$usage) {
            $usage = new MetricUsage();
            $usage->setCustomer($customer);
            $usage->setMetric($metric);
            $usage->setValue(0.0);
        }

        return $usage;
    }
}
