<?php

declare(strict_types=1);

namespace App\Common;

use Generator;

class Reader
{
    public function read(string $filePath): Generator
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \Exception("File does not exist or is not readable: $filePath");
        }

        $handle = fopen($filePath, "r");

        if (!$handle) {
            throw new \Exception("Could not open the file: $filePath");
        }

        try {
            while (!feof($handle) && ($line = fgets($handle)) !== false) {
                yield $line;
            }
        } finally {
            fclose($handle);
        }
    }
}