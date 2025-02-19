<?php

declare(strict_types=1);


namespace App;

use App\Bin\BinProvider;
use App\Commission\CommissionCalculator;
use App\Common\Reader;
use App\Currency\CurrencyExchanger;
use App\VO\Transaction;

class App
{
    private BinProvider  $binProvider;
    private CurrencyExchanger $currencyExchanger;

    private Reader $reader;

    private string $baseCurrency;

    public function __construct(
            BinProvider $binProvider,
            CurrencyExchanger $currencyExchanger,
            Reader $reader,
            string $baseCurrency = 'EUR'
    ) {
        $this->binProvider       = $binProvider;
        $this->currencyExchanger = $currencyExchanger;
        $this->reader            = $reader;
        $this->baseCurrency      = $baseCurrency;
    }

    public function run(string $filePath): void
    {
        foreach ($this->reader->read($filePath) as $row) {
            try {
                if (!$row) {
                    continue;
                }

                $transaction          = new Transaction($row);
                $amount               = $this->currencyExchanger->convert(
                        $transaction->getAmount(),
                        $transaction->getCurrency(),
                        $this->baseCurrency
                );
                $commissionCalculator = new CommissionCalculator($this->binProvider->fetchBin($transaction->getBin()));

                echo round($commissionCalculator->calculate($amount), 2) . PHP_EOL;
            }
            catch (\Throwable $e) {
                echo $e . PHP_EOL;
            }
        }
    }
}