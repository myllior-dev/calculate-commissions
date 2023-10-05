<?php

namespace App\Commands;

use App\Services\CommissionCalculator;
use LaravelZero\Framework\Commands\Command;
use App\Services\TransactionsService\Transaction;
use App\Services\TransactionsService\TransactionsService;
use App\Services\BinService\Exceptions\BinDetailsNotFoundException;

final class CalculateCommissionsCommand extends Command
{
    public const SIGNATURE = 'calculate-commissions';

    protected $signature = self::SIGNATURE;

    protected $description = 'Calculate commissions for already made transactions';

    /**
     * @throws BinDetailsNotFoundException
     */
    public function handle(TransactionsService $transactionsService, CommissionCalculator $commissionCalculator): int
    {
        $transactions = $transactionsService->getTransactions();

        $transactions = $commissionCalculator->calculate($transactions);

        /** @var Transaction $transaction */
        foreach ($transactions as $transaction) {
            $this->info($transaction->getCommission());
        }

        return self::SUCCESS;
    }
}
