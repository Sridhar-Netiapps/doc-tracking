<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccountOpeningDocumentFactory extends Factory
{
    public function definition(): array
    {
        $regions = [
            'South' => 1,
            'North' => 2,
            'East'  => 3,
            'West'  => 4,
        ];
    
        $regionName = $this->faker->randomElement(array_keys($regions));
        return [
            'unique_ref_no' => 'REF' . fake()->unique()->numberBetween(1000, 9999),
            'region' => $regionName,
            'branch_code' => $regions[$regionName].$this->faker->numberBetween(111, 119),
            'branch_name' => fake()->city(),
            'cif_id' => 'CIF' . fake()->unique()->numberBetween(100000, 999999),
            'account_number' => 'ACC' . fake()->unique()->numberBetween(1000000, 9999999),
            'customer_name' => fake()->name(),
            'account_creation_date' => fake()->dateTimeBetween('-1 week', 'now'),
            'scheme' => fake()->word(),
            'pgk_no' => fake()->word(),
            'channel' => fake()->randomElement(['Swagat', 'HHD', 'CRM']),
            'barcode' => NULL,
            'type_of_account_opening' => fake()->randomElement(['Esign', 'Manual']),
            'business_category' => fake()->randomElement(['Micro Banking', 'Branch Banking']),
        ];
    }
}
