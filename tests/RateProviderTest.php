<?php

declare(strict_types=1);


namespace tests;

use PHPUnit\Framework\TestCase;
use App\Rate\TestRateProvider;
use App\Rate\MainRateProvider;


class RateProviderTest extends TestCase
{
    public function testRateReturnsCorrectValue()
    {
        $rates = [
                'EUR' => 0.85,
                'GBP' => 0.75,
                'JPY' => 110.5,
        ];

        $rateProvider = new TestRateProvider($rates);

        $this->assertEquals(0.85, $rateProvider->fetchRate('EUR','EUR'));
        $this->assertEquals(0.75, $rateProvider->fetchRate('EUR','GBP'));
        $this->assertEquals(110.5, $rateProvider->fetchRate('EUR','JPY'));
    }

    public function testRateReturnsZeroForUnknownCurrency()
    {
        $rateProvider = new TestRateProvider([
                'EUR' => 0.85,
                'GBP' => 0.75,
        ]);

        $this->assertEquals(0.0, $rateProvider->fetchRate('EUR','INR'));
    }

    public function testRateProviderMock()
    {
        $mock = $this->createMock(MainRateProvider::class);
        $mock->method('fetchRate')->willReturnMap([
                ['EUR', 'EUR', 0.85],
                ['EUR', 'GBP', 0.75]
        ]);

        $this->assertEquals(0.85, $mock->fetchRate('EUR','EUR'));
        $this->assertEquals(0.75, $mock->fetchRate('EUR','GBP'));
    }
}