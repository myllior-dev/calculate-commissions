<?php

namespace App\Services\BinService;

final class BinDetails
{
    public function __construct(private readonly string $alpha2)
    {
    }

    public function getAlpha2(): string
    {
        return $this->alpha2;
    }
}
