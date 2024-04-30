<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace App\Payment\ExchangeRates;

use App\Repository\ExchangeRatesRepositoryInterface;
use Brick\Money\ExchangeRateProvider;

class BricksExchangeRateProvider implements ExchangeRateProvider
{
    public function __construct(private ExchangeRatesRepositoryInterface $exchangeRatesRepository)
    {
    }

    public function getExchangeRate(string $sourceCurrencyCode, string $targetCurrencyCode)
    {
        $exchangeRate = $this->exchangeRatesRepository->getByCode($targetCurrencyCode, $sourceCurrencyCode);

        return $exchangeRate->getExchangeRate();
    }
}
