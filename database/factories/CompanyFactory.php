<?php

namespace Database\Factories;

use App\Enums\CompanyTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->company,
            "address" => fake()->address,
            "phone" => fake()->phoneNumber,
            "type" => fake()->randomElement(CompanyTypeEnum::cases()),
            "contact_person" => fake()->name,
            "email" => fake()->unique()->companyEmail,
            "website" => "https://" . fake()->unique()->domainName()
        ];
    }
}
