<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Trait\Logger;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    use Logger;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $companies = Company::query();

        $companies->when($request->type, function ($q) use ($request) {
            return $q->where('type', $request->type);
        });

        return CompanyResource::collection($companies->paginate($request->per_page ?? 10));
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
