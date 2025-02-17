<?php

declare(strict_types=1);


namespace tests;

use App\VO\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testValidJsonCreatesTransaction()
    {
        $jsonData = json_encode([
                'bin'      => '45717360',
                'amount'   => 100.50,
                'currency' => 'EUR',
        ]);

        $transaction = new Transaction($jsonData);

        $this->assertEquals('45717360', $transaction->getBin());
        $this->assertEquals(100.50, $transaction->getAmount());
        $this->assertEquals('EUR', $transaction->getCurrency());
    }

    public function testInvalidJsonThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        new Transaction('{invalid json}');
    }

    public function testMissingBinThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required field: bin');

        $jsonData = json_encode([
                'amount'   => 100.50,
                'currency' => 'EUR',
        ]);

        new Transaction($jsonData);
    }

    public function testMissingAmountThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required field: amount');

        $jsonData = json_encode([
                'bin'      => '45717360',
                'currency' => 'EUR',
        ]);

        new Transaction($jsonData);
    }

    public function testMissingCurrencyThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required field: currency');

        $jsonData = json_encode([
                'bin'    => '45717360',
                'amount' => 100.50,
        ]);

        new Transaction($jsonData);
    }
}