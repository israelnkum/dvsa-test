<?php

namespace Database\Factories;

use App\Models\Company;
use Faker\Provider\FakeCar;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $companyId = Company::query()->inRandomOrder()->first()->id;

        $faker = (new \Faker\Factory())::create();
        $faker->addProvider(new FakeCar($faker));

        return [
            "company_id" => $companyId,
            "make" => $faker->vehicleType(),
            "model" => $faker->vehicleModel(),
            "registration_number" => $faker->vehicleRegistration(),
        ];
    }
}
