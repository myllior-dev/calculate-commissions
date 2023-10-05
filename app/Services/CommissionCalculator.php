<?php

namespace App\Services;

use App\Services\BinService\BinService;
use App\Services\CurrencyService\CurrencyService;
use App\Services\TransactionsService\Transaction;
use App\Services\BinService\Exceptions\BinDetailsNotFoundException;

class CommissionCalculator
{
    private const EU_COMMISSION = 0.01;

    private const NON_EU_COMMISSION = 0.02;

    private const DEFAULT_RATE = 0;

    private array $euCountryCodes;

    public function __construct(
        private readonly BinService $binService,
        private readonly CurrencyService $currencyService,
    ) {
        $this->euCountryCodes = config('settings.eu_country_codes');
    }

    /**
     * @param array<Transaction> $transactions
     *
     * @throws BinDetailsNotFoundException
     */
    public function calculate(array $transactions): array
    {
        $rates = $this->currencyService->getLatestRates();

        /** @var Transaction $transaction */
        foreach ($transactions as $transaction) {
            $transactionAmount = $transaction->getAmount();
            $transactionCurrency = $transaction->getCurrency();

            $binDetails = $this->binService->getBinDetails($transaction->getBin());

            $rate = $rates[$transactionCurrency] ?? self::DEFAULT_RATE;

            $fixedAmount = $transactionCurrency === 'EUR' || $rate == self::DEFAULT_RATE
                ? $transactionAmount
                : $transactionAmount / $rate;

            $commission = in_array($binDetails->getAlpha2(), $this->euCountryCodes)
                ? self::EU_COMMISSION
                : self::NON_EU_COMMISSION;

            $transactionCommission = bcmul($fixedAmount, $commission, 2);

            $transaction->setCommission($transactionCommission);
        }

        return $transactions;
    }
}
