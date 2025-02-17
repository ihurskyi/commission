<?php

declare(strict_types=1);

namespace App\Bin;

use App\Exception\BinException;
use GuzzleHttp\Client;

class MainBinProvider implements BinProvider
{
    public function fetchBin(string $code): string
    {
        try {
            $response = (new Client())->get("https://lookup.binlist.net/$code", [
                    "headers" => ["Accept-Version" => 3]
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if (!array_key_exists('country', $data) || !array_key_exists('alpha2', $data['country'])) {
                throw new \Exception("Country for bin {$code} not found.");
            }

            return $data['country']['alpha2'];
        } catch (\Throwable $e) {
            throw new BinException($e->getMessage());
        }
    }
}