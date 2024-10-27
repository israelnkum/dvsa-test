<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Trait\Helpers;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Http\Resources\VehicleResource;
use App\Models\Vehicle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class VehicleController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     * @throws \JsonException
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $cacheKey = $this->getCacheKey('vehicles', $request->all());

        $companies = Cache::remember($cacheKey, now()->addDay(), static function () use ($request) {
            return Vehicle::query()->paginate($request->per_page ?? 10);
        });

        return VehicleResource::collection($companies);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request): ApiResponse
    {
        try {
            $vehicle = Vehicle::create($request->validated());

            return ApiResponse::success(VehicleResource::make($vehicle), 201);
        } catch (Exception $exception) {
            $this->logError("Add Vehicle Error:", $exception);

            return ApiResponse::error("Could not add vehicle.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle): ApiResponse
    {
        return ApiResponse::success(VehicleResource::make($vehicle));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): ApiResponse
    {
        try {
            $vehicle->update($request->validated());

            return ApiResponse::success(VehicleResource::make($vehicle));
        } catch (Exception $exception) {
            $this->logError("Update Vehicle Error: ", $exception);

            return ApiResponse::error("Error updating vehicle.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle): ApiResponse
    {
        try {
            $vehicle->delete();

            return ApiResponse::success([
                'message' => 'Vehicle Deleted'
            ]);

        } catch (Exception $exception) {
            $this->logError("Delete Vehicle Error: ", $exception);

            return ApiResponse::error("Could not delete vehicle.");
        }
    }
}
