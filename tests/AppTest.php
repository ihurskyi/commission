<?php

declare(strict_types=1);


namespace tests;

use App\App;
use App\Bin\BinProvider;
use App\Common\Reader;
use App\Rate\RateProvider;
use Generator;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testEUCountryRun()
    {
        // Capture output
        ob_start();

        $app = new App(
                $this->getMockBinProvider('DK'),
                $this->getMockRateProvider(1.048817),
                $this->getMockReader()
        );

        $app->run('test-file.txt');

        $output = ob_get_clean();

        $this->assertEquals("0.95" . PHP_EOL . "0.5" . PHP_EOL, $output);
    }

    public function testNonEUCountryRun()
    {
        // Capture output
        ob_start();

        // Create App with mocks
        $app = new App(
                $this->getMockBinProvider('JP'),
                $this->getMockRateProvider(1.048817),
                $this->getMockReader()
        );

        $app->run('test-file.txt');

        $output = ob_get_clean();

        $this->assertEquals("1.91" . PHP_EOL . "1" . PHP_EOL, $output);
    }

    private function getMockBinProvider(string $country): object
    {
        $binProviderMock = $this->createMock(BinProvider::class);
        $binProviderMock->method('fetchBin')->willReturn($country);

        return $binProviderMock;
    }

    private function getMockRateProvider(float $rate): object
    {
        $rateProviderMock = $this->createMock(RateProvider::class);
        $rateProviderMock->method('fetchRate')->willReturn($rate);

        return $rateProviderMock;
    }

    private function getMockReader(): object
    {
        $readerMock = $this->createMock(Reader::class);
        $readerMock->method('read')->willReturn($this->mockFileReader());

        return $readerMock;
    }

    private function mockFileReader(): Generator
    {
        yield '{"bin":"45717360","amount":100.00,"currency":"USD"}';
        yield '{"bin":"516793","amount":50.00,"currency":"EUR"}';
    }
}