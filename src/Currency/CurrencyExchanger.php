<?php

declare(strict_types=1);


namespace App\Currency;

use App\Rate\RateProvider;

class CurrencyExchanger
{
    private RateProvider $rateProvider;

    public function __construct(RateProvider $rateProvider)
    {
        $this->rateProvider = $rateProvider;
    }

    public function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        return $amount / $this->rateProvider->fetchRate($toCurrency, $fromCurrency);
    }
}