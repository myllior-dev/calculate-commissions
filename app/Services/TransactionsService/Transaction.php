<?php

namespace App\Services\TransactionsService;

final class Transaction
{
    public function __construct(
        private readonly string $bin,
        private readonly float $amount,
        private readonly string $currency,
        private null|float $commission = null,
    ) {
    }

    public function getCommission(): null|float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): void
    {
        $this->commission = $commission;
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
