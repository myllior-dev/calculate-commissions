<?php

namespace App\Services\CurrencyService;

use Illuminate\Support\Facades\Http;

final class ExchangeRates implements CurrencyService
{
    public function getLatestRates(): array
    {
        $response = Http::get('https://api.exchangeratesapi.io/latest');

        $body = $response->json();

        if (! $body['success']) {
            return [];
        }

        return $response['rates'];
    }
}
