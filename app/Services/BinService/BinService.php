<?php

namespace App\Services\BinService;

use App\Services\BinService\Exceptions\BinDetailsNotFoundException;

interface BinService
{
    /**
     * @throws BinDetailsNotFoundException
     */
    public function getBinDetails(string $bin): BinDetails;
}
