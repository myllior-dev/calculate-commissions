<?php

namespace Tests\Feature\Services\BinService;

use Tests\TestCase;
use App\Services\BinService\BinDetails;
use App\Services\BinService\BinListNet;
use App\Services\BinService\Exceptions\BinDetailsNotFoundException;

final class BinListNetTest extends TestCase
{
    private BinListNet $binListNet;

    protected function setUp(): void
    {
        parent::setUp();

        $this->binListNet = resolve(BinListNet::class);
    }

    /**
     * @throws BinDetailsNotFoundException
     */
    public function testGetBinDetails(): void
    {
        $result = $this->binListNet->getBinDetails('516793');

        $this->assertEquals(new BinDetails('LT'), $result);
    }

    public function testGetBinDetailsInvalidBin(): void
    {
        $this->expectException(BinDetailsNotFoundException::class);

        $this->binListNet->getBinDetails('2323223');
    }
}
