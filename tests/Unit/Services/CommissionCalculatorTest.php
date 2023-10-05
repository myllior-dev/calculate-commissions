<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use Mockery\MockInterface;
use App\Services\CommissionCalculator;
use App\Services\BinService\BinDetails;
use App\Services\BinService\BinService;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Services\CurrencyService\CurrencyService;
use App\Services\TransactionsService\Transaction;
use App\Services\BinService\Exceptions\BinDetailsNotFoundException;

final class CommissionCalculatorTest extends TestCase
{
    /**
     * @throws BinDetailsNotFoundException
     */
    #[DataProvider('calculateCommissionDataProvider')]
    public function testCalculateCommission(
        string $currency,
        array $latestRates,
        BinDetails $binDetails,
        float $calculatedCommission,
    ): void {
        $transaction = new Transaction('516793', 50.0, $currency);
        $transactions = [$transaction];

        $this->mockCurrencyService($latestRates);
        $this->mockBinService($transaction, $binDetails);

        $result = $this->calculateCommissions($transactions);

        $this->assertCount(1, $result);
        $this->assertEquals(new Transaction('516793', 50.0, $currency, $calculatedCommission), $result[0]);
    }

    public static function calculateCommissionDataProvider(): array
    {
        return [
            'calculate_eu_transaction_with_eur_currency_and_provided_rate' => ['EUR', ['EUR' => 0.55], new BinDetails('EE'), 0.5],
            'calculate_eu_transaction_with_eur_currency_without_rate' => ['EUR', [], new BinDetails('EE'), 0.5],
            'calculate_eu_transaction_with_usd_currency_and_provided_rate' => ['USD', ['USD' => 0.55], new BinDetails('EE'), 0.9],
            'calculate_eu_transaction_with_usd_currency_without_rate' => ['USD', [], new BinDetails('EE'), 0.5],
            'calculate_non_eu_transaction_with_eur_currency_and_provided_rate' => ['EUR', ['EUR' => 0.55], new BinDetails('test'), 1.0],
            'calculate_non_eu_transaction_with_eur_currency_without_rate' => ['EUR', [], new BinDetails('test'), 1.0],
            'calculate_non_eu_transaction_with_usd_currency_and_provided_rate' => ['USD', ['USD' => 0.55], new BinDetails('test'), 1.81],
            'calculate_non_eu_transaction_with_usd_currency_without_rate' => ['USD', [], new BinDetails('test'), 1.0],
        ];
    }

    private function mockCurrencyService(array $expectedResult): void
    {
        $this->mock(
            CurrencyService::class,
            function (MockInterface $mock) use ($expectedResult): void {
                $mock
                    ->expects('getLatestRates')
                    ->once()
                    ->withNoArgs()
                    ->andReturn($expectedResult);
            },
        );
    }

    private function mockBinService(Transaction $expectedTransaction, BinDetails $expectedBinDetails): void
    {
        $this->mock(
            BinService::class,
            function (MockInterface $mock) use ($expectedTransaction, $expectedBinDetails): void {
                $mock
                    ->expects('getBinDetails')
                    ->once()
                    ->withArgs([$expectedTransaction->getBin()])
                    ->andReturn($expectedBinDetails);
            },
        );
    }

    /**
     * @throws BinDetailsNotFoundException
     */
    private function calculateCommissions(array $transactions): array
    {
        /** @var CommissionCalculator $commissionCalculator */
        $commissionCalculator = (resolve(CommissionCalculator::class));

        return $commissionCalculator->calculate($transactions);
    }
}
