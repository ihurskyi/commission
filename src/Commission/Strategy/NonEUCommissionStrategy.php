<?php

declare(strict_types=1);


namespace App\Commission\Strategy;

class NonEUCommissionStrategy implements CommissionStrategy
{
    public function calculate(float $amount): float
    {
        return $amount * 0.02;
    }
}