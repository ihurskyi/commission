<?php

declare(strict_types=1);


namespace App\Bin;

class TestBinProvider implements BinProvider
{
    private array $binData;

    public function __construct(array $binData = [])
    {
        $this->binData = $binData;
    }

    public function fetchBin(string $code): string
    {
        return $this->binData[$code] ?? '';
    }
}