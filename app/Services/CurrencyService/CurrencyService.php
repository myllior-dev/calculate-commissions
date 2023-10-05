<?php

namespace App\Services\CurrencyService;

interface CurrencyService
{
    public function getLatestRates(): null|array;
}
