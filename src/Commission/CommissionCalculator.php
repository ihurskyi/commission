<?php

declare(strict_types=1);


namespace App\Commission;

use App\Commission\Strategy\CommissionStrategy;
use App\Commission\Strategy\EUCommissionStrategy;
use App\Commission\Strategy\NonEUCommissionStrategy;

class CommissionCalculator
{
    private CommissionStrategy $strategy;

    public function __construct(string $country)
    {
        $this->strategy = $this->isEU($country) ? new EUCommissionStrategy() : new NonEUCommissionStrategy();
    }

    public function calculate(float $amount): float
    {
        return $this->strategy->calculate($amount);
    }

    private function isEU(string $code): bool
    {
        return in_array($code, [
                'AT',
                'BE',
                'BG',
                'CY',
                'CZ',
                'DE',
                'DK',
                'EE',
                'ES',
                'FI',
                'FR',
                'GR',
                'HR',
                'HU',
                'IE',
                'IT',
                'LT',
                'LU',
                'LV',
                'MT',
                'NL',
                'PL',
                'PT',
                'RO',
                'SE',
                'SI',
                'SK',
        ]);
    }
}