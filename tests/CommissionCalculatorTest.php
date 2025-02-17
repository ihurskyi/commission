<?php

declare(strict_types=1);

namespace tests;

use PHPUnit\Framework\TestCase;
use App\Commission\CommissionCalculator;


class CommissionCalculatorTest extends TestCase
{
    public function testEUCommissionCalculation()
    {
        $calculator = new CommissionCalculator('DE');

        $this->assertEquals(10.0, $calculator->calculate(1000));
    }

    public function testNonEUCommissionCalculation()
    {
        $calculator = new CommissionCalculator('JP');

        $this->assertEquals(20.0, $calculator->calculate(1000));
    }
}