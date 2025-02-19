<?php

declare(strict_types=1);


namespace tests;


use App\Currency\CurrencyExchanger;
use App\Rate\RateProvider;
use PHPUnit\Framework\TestCase;


class CurrencyExchangerTest extends TestCase
{
    private object $rateProviderMock;
    private object $currencyExchanger;

    protected function setUp(): void
    {
        $this->rateProviderMock  = $this->createMock(RateProvider::class);
        $this->currencyExchanger = new CurrencyExchanger($this->rateProviderMock);
    }

    public function testConvertSameCurrencyReturnsSameAmount()
    {
        $amount   = 100.0;
        $currency = 'EUR';

        $result = $this->currencyExchanger->convert($amount, $currency, $currency);

        $this->assertEquals($amount, $result);
    }

    public function testConvertDifferentCurrencyUsesExchangeRate()
    {
        $amount       = 100.0;
        $fromCurrency = 'USD';
        $toCurrency   = 'EUR';
        $exchangeRate = 1.1;

        $this->rateProviderMock
                ->method('fetchRate')
                ->with($toCurrency, $fromCurrency)
                ->willReturn($exchangeRate);

        $result = $this->currencyExchanger->convert($amount, $fromCurrency, $toCurrency);

        $this->assertEquals(round($amount / $exchangeRate, 2), round($result, 2));
    }
}