<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], static function () {
    Route::apiResource('vehicles', VehicleController::class);

    Route::get('companies/{company}/vehicles', [CompanyController::class, 'getVehicles']);
    Route::apiResource('companies', CompanyController::class);
});
