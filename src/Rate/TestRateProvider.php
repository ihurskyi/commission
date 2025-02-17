<?php

declare(strict_types=1);


namespace App\Rate;

class TestRateProvider implements RateProvider
{
    private array $rates;

    public function __construct(array $rates = [])
    {
        $this->rates = $rates;
    }

    public function fetchRate(string $baseCurrency, string $currency): float
    {
        return $this->rates[$currency] ?? 0.0;
    }
}