<?php

declare(strict_types=1);

namespace App\Bin;

interface BinProvider
{
    public function fetchBin(string $code): string;
}