<?php

declare(strict_types=1);


namespace tests;

use PHPUnit\Framework\TestCase;
use App\Bin\TestBinProvider;
use App\Bin\MainBinProvider;


class BinProviderTest extends TestCase
{
    public function testBinReturnsCorrectCountry()
    {
        $binData = [
                '45717360' => 'DE',
                '516793'   => 'CZ',
                '41417360' => 'US',
        ];

        $binProvider = new TestBinProvider($binData);

        $this->assertEquals('DE', $binProvider->fetchBin('45717360'));
        $this->assertEquals('CZ', $binProvider->fetchBin('516793'));
        $this->assertEquals('US', $binProvider->fetchBin('41417360'));
    }

    public function testBinReturnsNullForUnknownBin()
    {
        $binProvider = new TestBinProvider([
                '45717360' => 'DE'
        ]);

        $this->assertEquals('', $binProvider->fetchBin('999999'));
    }

    public function testBinProviderMock()
    {
        $mock = $this->createMock(MainBinProvider::class);
        $mock->method('fetchBin')->willReturnMap([
                ['45717360', 'DE'],
                ['516793', 'CZ']
        ]);

        $this->assertEquals('DE', $mock->fetchBin('45717360'));
        $this->assertEquals('CZ', $mock->fetchBin('516793'));
    }
}