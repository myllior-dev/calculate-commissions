<?php

namespace App\Services\BinService;

use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use App\Services\BinService\Exceptions\BinDetailsNotFoundException;

final class BinListNet implements BinService
{
    private const BASE_URL = 'https://lookup.binlist.net/';

    /**
     * @throws BinDetailsNotFoundException
     */
    public function getBinDetails(string $bin): BinDetails
    {
        $response = Http::get(self::BASE_URL . $bin);

        if ($response->status() !== Response::HTTP_OK) {
            throw new BinDetailsNotFoundException('Details not found for: ' . $bin);
        }

        $body = $response->json();

        return new BinDetails($body['country']['alpha2']);
    }
}
