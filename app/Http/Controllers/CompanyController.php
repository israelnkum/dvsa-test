<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Trait\Helpers;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyVehicleResource;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use JsonException;

class CompanyController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     * @throws JsonException
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $cacheKey = $this->getCacheKey('companies', $request->all());

        $companies = Cache::remember($cacheKey, now()->addDay(), static function () use ($request) {

            $query = Company::query();

            $query->when($request->type, function ($q) use ($request) {
                return $q->where('type', strtoupper($request->type));
            });

            return $query->paginate($request->per_page ?? 10);
        });

        if ($request->has('vehicles') && $request->query('vehicles') === "false") {
            return CompanyResource::collection($companies)->additional([
                'success' => true,
                'message' => "Companies retrieved successfully."
            ]);
        }

        return CompanyVehicleResource::collection($companies)->additional([
            'success' => true,
            'message' => "Companies retrieved successfully."
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request): ApiResponse
    {
        try {
            $company = Company::create($request->validated());

            return ApiResponse::success(CompanyResource::make($company), 201);
        } catch (Exception $exception) {
            $this->logError("Add Company Error:", $exception);

            return ApiResponse::error("Could not add company.");
        }
    }

    public function getVehicles(Company $company): ApiResponse
    {
        return ApiResponse::success(CompanyVehicleResource::make($company));
    }


    /**
     * Display the specified resource.
     */
    public function show(Company $company): ApiResponse
    {
        return ApiResponse::success(CompanyResource::make($company));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company): ApiResponse
    {
        try {
            $company->update($request->validated());

            return ApiResponse::success(CompanyResource::make($company));
        } catch (Exception $exception) {
            $this->logError("Update Company Error: ", $exception);

            return ApiResponse::error("Error updating company.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): ApiResponse
    {
        DB::beginTransaction();
        try {
            $company->vehicles()->delete();
            $company->delete();

            DB::commit();

            return ApiResponse::success([
                'message' => 'Company Deleted'
            ]);

        } catch (Exception $exception) {
            DB::rollBack();

            $this->logError("Delete Company Error: ", $exception);

            return ApiResponse::error("Could not delete company.");
        }
    }
}
