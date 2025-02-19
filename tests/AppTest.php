<?php

declare(strict_types=1);


namespace tests;

use App\App;
use App\Bin\BinProvider;
use App\Common\Reader;
use App\Currency\CurrencyExchanger;
use App\Rate\RateProvider;
use Generator;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private object $binProviderMock;
    private object $currencyExchangerMock;
    private object $readerMock;
    private App    $app;

    protected function setUp(): void
    {
        $this->binProviderMock       = $this->createMock(BinProvider::class);
        $this->currencyExchangerMock = $this->createMock(CurrencyExchanger::class);
        $this->readerMock            = $this->createMock(Reader::class);

        $this->app = new App($this->binProviderMock, $this->currencyExchangerMock, $this->readerMock);
    }

    private function generateTransactions(array $transactions): Generator
    {
        foreach ($transactions as $transaction) {
            yield $transaction;
        }
    }

    public function testNonEUCountryRun()
    {
        $mockTransactionJson = json_encode([
                'bin'      => '45717360',
                'amount'   => 100.00,
                'currency' => 'USD',
        ]);

        $this->readerMock->method('read')->willReturn($this->generateTransactions([$mockTransactionJson]));
        $this->binProviderMock->method('fetchBin')->willReturn('JP');
        $this->currencyExchangerMock->method('convert')->willReturn(95.34);

        ob_start();
        $this->app->run('file.txt');
        $output = ob_get_clean();

        $this->assertEquals(1.91 . PHP_EOL, $output);
    }

    public function testEUCountryRun()
    {
        $mockTransactionJson = json_encode([
                'bin'      => '516793',
                'amount'   => 100.00,
                'currency' => 'GBP',
        ]);

        $this->readerMock->method('read')->willReturn($this->generateTransactions([$mockTransactionJson]));
        $this->binProviderMock->method('fetchBin')->willReturn('DK');
        $this->currencyExchangerMock->method('convert')->willReturn(120.48);


        ob_start();
        $this->app->run('file.txt');
        $output = ob_get_clean();

        $this->assertEquals(1.2 . PHP_EOL, $output);
    }

    public function testRunThrowsExceptionForMissingCurrencyField()
    {
        $mockTransactionJson = json_encode([
                'bin'    => '45717360',
                'amount' => 100.50,
        ]);

        $this->readerMock->method('read')->willReturn($this->generateTransactions([$mockTransactionJson]));

        ob_start();
        $this->app->run('file.txt');
        $output = ob_get_clean();

        $this->assertStringContainsString('Missing required field: currency', $output);
    }
}