<?php

namespace Botble\Course\Services\ExchangeRates;

use Exception;
use Illuminate\Support\Collection;

interface ExchangeRateInterface
{
    /**
     * @throws Exception
     */
    public function getCurrentExchangeRate(): Collection;

    public function cacheExchangeRates(): array;
}
