<?php

declare(strict_types=1);


namespace App\Rate;

interface RateProvider
{
    public function fetchRate(string $baseCurrency, string $currency): float;
}