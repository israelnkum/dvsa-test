<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BayControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_parking_bays(): void
    {
        $bayLengths = [5, 15, 5, 7, 3, 10, 5, 3, 7, 5];
        $vehicleLength = 25;

        $response = $this->postJson('/api/v1/get-bays', [
            'bayLengths' => $bayLengths,
            'vehicleLength' => $vehicleLength,
        ]);

        $response->assertStatus(200);

        Log::info("Packing Bay's", $response->json());
    }
}
