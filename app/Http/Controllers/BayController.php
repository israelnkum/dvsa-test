<?php

namespace App\Http\Controllers;

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

            foreach ($bayLengths as $i => $iValue) {
                $currentBays[] = $i + 1;
                $currentSum += $iValue;

                // Check if the current sum is enough for the vehicle
                if ($currentSum >= $vehicleLength) {
                    $result[] = $currentBays;
                }

                // Sliding window to adjust the sequence from the left
                while ($currentSum >= $vehicleLength && count($currentBays) > 1) {
                    $currentSum -= $bayLengths[array_shift($currentBays) - 1];
                    if ($currentSum >= $vehicleLength) {
                        $result[] = $currentBays;
                    }
                }
            }

            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }
}
