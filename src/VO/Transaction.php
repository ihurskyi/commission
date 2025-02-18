<?php

declare(strict_types=1);


namespace App\VO;

class Transaction
{
    private string $bin;
    private float  $amount;

    private string $currency;

    public function __construct(string $jsonData)
    {
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON format: ' . json_last_error_msg());
        }

        foreach (['bin', 'amount', 'currency'] as $field) {
            if (!array_key_exists($field, $data)) {
                throw new \InvalidArgumentException("Missing required field: $field");
            }
        }

        $this->bin      = $data['bin'];
        $this->amount   = (float) $data['amount'];
        $this->currency = strtoupper($data['currency']);
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}