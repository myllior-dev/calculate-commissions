<?php

namespace Tests\Feature\Services\CurrencyService;

use Tests\TestCase;
use App\Services\CurrencyService\ExchangeRates;

final class ExchangeRatesTest extends TestCase
{
    private ExchangeRates $exchangeRates;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exchangeRates = resolve(ExchangeRates::class);
    }

    public function testGetLatestRatesUnauthorized(): void
    {
        $result = $this->exchangeRates->getLatestRates();

        $this->assertSame([], $result);
    }
}
