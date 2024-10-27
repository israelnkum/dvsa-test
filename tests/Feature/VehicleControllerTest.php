<?php

namespace Tests\Feature;

use App\Enums\CompanyTypeEnum;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\Feature\Trait\Auth;
use Tests\TestCase;

class VehicleControllerTest extends TestCase
{
    use RefreshDatabase, Auth;

    private array $companyData = [
        "name" => "Test Company",
        "address" => "123 Test Address",
        "phone" => "0544513074",
        "type" => CompanyTypeEnum::HEAVY_GOODS_VEHICLE,
        "contact_person" => "John Doe",
        "email" => "john@example.com",
        "website" => "https://example.com",
    ];

    private array $vehicleData = [
        "make" => "Toyota",
        "model" => "Corolla",
        "registration_number" => "ABC123"
    ];

    private function createCompany()
    {
        $company = $this->postJson("/api/v1/companies", $this->companyData);

        $company->assertStatus(ResponseAlias::HTTP_CREATED);
        $this->assertDatabaseHas("companies", $this->companyData);

        return $company->json("data");
    }

    public function test_get_all_vehicles(): void
    {
        $this->authenticateUser();
        $response = $this->getJson("/api/v1/vehicles");

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function test_create_a_new_vehicle(): void
    {
        $this->authenticateUser();
        $company = $this->createCompany();

        $this->vehicleData["company_id"] = $company["id"];
        $response = $this->postJson("/api/v1/vehicles", $this->vehicleData);

        $response->assertStatus(ResponseAlias::HTTP_CREATED);
        $this->assertDatabaseHas("vehicles", $this->vehicleData);
    }

    public function test_get_single_vehicle(): void
    {
        $this->authenticateUser();

        $company = $this->createCompany();

        $this->vehicleData["company_id"] = $company["id"];

        $vehicle = Vehicle::create($this->vehicleData);

        $response = $this->getJson("/api/v1/vehicles/{$vehicle->id}");

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function test_update_a_vehicle(): void
    {
        $this->authenticateUser();

        $company = $this->createCompany();

        $this->vehicleData["company_id"] = $company["id"];

        $vehicle = Vehicle::create($this->vehicleData);
        $update = ["make" => "Chevrolet", "company_id" => $company["id"]];

        $response = $this->putJson("/api/v1/vehicles/{$vehicle->id}", $update);

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $this->assertDatabaseHas("vehicles", array_merge($this->vehicleData, $update));
    }

    public function test_delete_a_vehicle(): void
    {
        $this->authenticateUser();

        $company = $this->createCompany();

        $this->vehicleData["company_id"] = $company["id"];

        $vehicle = Vehicle::create($this->vehicleData);

        $response = $this->deleteJson("/api/v1/vehicles/{$vehicle->id}");

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $this->assertDatabaseMissing("vehicles", $vehicle->toArray());
    }
}
