<?php

namespace Tests\Unit\Services\TransactionsService;

use Tests\TestCase;
use App\Services\TransactionsService\Transaction;
use App\Services\TransactionsService\FileTransactionsService;

final class FileTransactionServiceTest extends TestCase
{
    private FileTransactionsService $fileTransactionsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileTransactionsService = resolve(FileTransactionsService::class);
    }

    public function testGetTransactions(): void
    {
        $result = $this->fileTransactionsService->getTransactions(storage_path('tests/transactions.txt'));

        $this->assertCount(1, $result);
        $this->assertEquals(new Transaction('45717360', 100.0, 'EUR'), $result[0]);
    }
}
