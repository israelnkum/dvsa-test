<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BayController extends Controller
{
    public function getParkingBays(Request $request): JsonResponse
    {
        $request->validate([
            'bayLengths' => 'required|array',
            'vehicleLength' => 'required|integer',
        ]);

        $bayLengths = $request->bayLengths;
        $vehicleLength = $request->vehicleLength;

        try {
            $result = [];
            $currentBays = [];
            $currentSum = 0;

            foreach ($bayLengths as $i => $bay) {
                $currentBays[] = $i + 1;
                $currentSum += $bay;

                // Check if the current sum is enough for the vehicle
                if ($currentSum >= $vehicleLength) {
                    $result[] = $currentBays;
                }

                while ($currentSum >= $vehicleLength && count($currentBays) > 1) {
                    $currentSum -= $bayLengths[array_shift($currentBays) - 1];
                    if ($currentSum >= $vehicleLength) {
                        $result[] = $currentBays;
                    }
                }
            }

            return ApiResponse::success($result);
        } catch (Exception $exception) {
            Log::error("Get bay Error:", [$exception]);

            return ApiResponse::error("Something went wrong");
        }
    }
}
