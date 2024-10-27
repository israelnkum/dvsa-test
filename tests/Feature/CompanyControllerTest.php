<?php

namespace Tests\Feature;

use App\Enums\CompanyTypeEnum;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\Feature\Trait\Auth;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    use RefreshDatabase, Auth;

    private array $data = [
        "name" => "Test Company",
        "address" => "123 Test St",
        "phone" => "1234567890",
        "type" => CompanyTypeEnum::HEAVY_GOODS_VEHICLE,
        "contact_person" => "John Doe",
        "email" => "john@example.com",
        "website" => "https://example.com",
    ];

    public function test_get_all_companies(): void
    {
        $this->authenticateUser();
        $response = $this->getJson("/api/v1/companies");

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function test_create_a_new_company(): void
    {
        $this->authenticateUser();
        $response = $this->postJson("/api/v1/companies", $this->data);

        $response->assertStatus(ResponseAlias::HTTP_CREATED);
        $this->assertDatabaseHas("companies", $this->data);
    }

    public function test_get_single_company(): void
    {
        $this->authenticateUser();
        $company = Company::create($this->data);

        $response = $this->getJson("/api/v1/companies/{$company->id}");

        $response->assertStatus(ResponseAlias::HTTP_OK);
    }

    public function test_update_a_company(): void
    {
        $this->authenticateUser();
        $company = Company::create($this->data);
        $update = ["name" => "Updated Company", "type" => CompanyTypeEnum::HEAVY_GOODS_VEHICLE];

        $response = $this->putJson("/api/v1/companies/{$company->id}", $update);

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $this->assertDatabaseHas("companies", $update);
    }

    public function test_delete_a_company(): void
    {
        $this->authenticateUser();
        $company = Company::create($this->data);

        $response = $this->deleteJson("/api/v1/companies/{$company->id}");

        $response->assertStatus(ResponseAlias::HTTP_OK);
        $this->assertDatabaseMissing($company);
    }
}
