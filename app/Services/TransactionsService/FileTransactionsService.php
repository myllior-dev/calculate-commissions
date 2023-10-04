<?php

namespace App\Services\TransactionsService;

final class FileTransactionsService implements TransactionsService
{
    /**
     * @return array<Transaction>
     */
    public function getTransactions(null|string $path = null): array
    {
        $path = $path ?: storage_path('transactions.txt');

        $fileContent = explode("\n", file_get_contents($path));

        $transactions = [];

        foreach ($fileContent as $row) {
            if (! $row) {
                break;
            }

            $row = json_decode($row, true);

            $transactions[] = new Transaction($row['bin'], $row['amount'], $row['currency']);
        }

        return $transactions;
    }
}
