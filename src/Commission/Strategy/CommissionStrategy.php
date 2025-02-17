<?php

declare(strict_types=1);

namespace App\Commission\Strategy;

interface CommissionStrategy
{
    public function calculate(float $amount): float;
}