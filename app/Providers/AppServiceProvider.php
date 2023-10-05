<?php

namespace App\Providers;

use App\Services\BinService\BinListNet;
use App\Services\BinService\BinService;
use Illuminate\Support\ServiceProvider;
use App\Services\CurrencyService\ExchangeRates;
use App\Services\CurrencyService\CurrencyService;
use App\Services\TransactionsService\TransactionsService;
use App\Services\TransactionsService\FileTransactionsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BinService::class, BinListNet::class);
        $this->app->bind(CurrencyService::class, ExchangeRates::class);
        $this->app->bind(TransactionsService::class, FileTransactionsService::class);
    }
}
