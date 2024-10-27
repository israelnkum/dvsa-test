<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\Vehicle;

class HomeController extends Controller
{
    public function getDashboardAnalytics(): ApiResponse
    {
        $companies = Company::count();
        $vehicles = Vehicle::count();

        return ApiResponse::success([
            'companies' => $companies,
            'vehicles' => $vehicles
        ]);
    }
}
