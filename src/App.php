<?php

declare(strict_types=1);


namespace App;

use App\Bin\BinProvider;
use App\Commission\CommissionCalculator;
use App\Common\Reader;
use App\Rate\RateProvider;
use App\VO\Transaction;

class App
{
    private BinProvider  $binProvider;
    private RateProvider $rateProvider;

    private Reader $reader;

    private string $baseCurrency;

    public function __construct(BinProvider $binProvider, RateProvider $rateProvider, Reader $reader, string $baseCurrency = 'EUR')
    {
        $this->binProvider  = $binProvider;
        $this->rateProvider = $rateProvider;
        $this->reader       = $reader;
        $this->baseCurrency = $baseCurrency;
    }

    public function run(string $filePath): void
    {
        foreach ($this->reader->read($filePath) as $row) {
            try {
                if (!$row) {
                    continue;
                }

                $transaction = new Transaction($row);
                $country     = $this->binProvider->fetchBin($transaction->getBin());

                $rate = ($transaction->getCurrency() === $this->baseCurrency)
                        ? 1.0
                        : $this->rateProvider->fetchRate($this->baseCurrency, $transaction->getCurrency());

                $amount               = $transaction->getAmount() / $rate;
                $commissionCalculator = new CommissionCalculator($country);

                echo round($commissionCalculator->calculate($amount), 2) . PHP_EOL;
            }
            catch (\Throwable $e) {
                echo $e . PHP_EOL;
            }
        }
    }
}