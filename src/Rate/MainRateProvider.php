<?php

declare(strict_types=1);


namespace App\Rate;

use App\Exception\RateException;
use GuzzleHttp\Client;

class MainRateProvider implements RateProvider
{
    private string $apiKey;

    private array $rates = [];

    public function __construct(string $apiKey)
    {
        if (!$apiKey) {
            throw new \InvalidArgumentException('Api key is required!');
        }

        $this->apiKey = $apiKey;
    }

    public function fetchRate(string $baseCurrency, string $currency): float
    {
        if ($rate = $this->getRate($currency)) {
            return $rate;
        }

        $this->rates = array_merge($this->rates, $this->fetchRateFromAPI($baseCurrency, $currency));

        return $this->getRate($currency);
    }

    private function fetchRateFromAPI(string $baseCurrency, string $currency): array
    {
        try {
            $response = (new Client())->get("https://api.exchangeratesapi.io/latest", [
                    'query'   => [
                            'base'    => $baseCurrency,
                            'symbols' => $this->commonCurrency($currency),
                    ],
                    "headers" => ["apikey" => $this->apiKey,],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if (!array_key_exists('rates', $data) || !array_key_exists($currency, $data['rates'])) {
                throw new \Exception("Exchange rate for {$currency} not found.");
            }

            return $data['rates'];
        } catch (\Throwable $e) {
            throw new RateException($e->getMessage());
        }
    }

    private function getRate(string $currency): ?float
    {
        return $this->rates[$currency] ?? null;
    }

    private function commonCurrency(string $currency): string
    {
        return implode(',', array_unique(['USD', 'GBP', 'JPY', $currency]));
    }
}