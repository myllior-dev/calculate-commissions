<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Mockery\MockInterface;
use App\Services\CommissionCalculator;
use App\Commands\CalculateCommissionsCommand;
use App\Services\TransactionsService\Transaction;
use App\Services\TransactionsService\TransactionsService;

final class CalculateCommissionsCommandTest extends TestCase
{
    public function testCalculateCommissions(): void
    {
        $initialTransactions = [new Transaction('516793', 100.00, 'EUR')];

        $calculatedTransaction = new Transaction('516793', 100.00, 'EUR');
        $calculatedTransaction->setCommission(10.00);
        $calculatedTransactions = [$calculatedTransaction];

        $this->mock(
            TransactionsService::class,
            function (MockInterface $mock) use ($initialTransactions): void {
                $mock
                    ->expects('getTransactions')
                    ->once()
                    ->withNoArgs()
                    ->andReturn($initialTransactions);
            },
        );

        $this->mock(
            CommissionCalculator::class,
            function (MockInterface $mock) use ($initialTransactions, $calculatedTransactions): void {
                $mock
                    ->expects('calculate')
                    ->once()
                    ->withArgs([$initialTransactions])
                    ->andReturn($calculatedTransactions);
            },
        );

        $this
            ->artisan(CalculateCommissionsCommand::SIGNATURE)
            ->expectsOutput(10.00)
            ->assertSuccessful();
    }
}
