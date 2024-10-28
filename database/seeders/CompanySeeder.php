<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory(100)
            ->has(Vehicle::factory()->count(100))
            ->create();
    }
}
