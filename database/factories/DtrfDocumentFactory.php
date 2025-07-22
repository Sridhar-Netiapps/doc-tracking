<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DtrfDocumentFactory extends Factory
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
            'account_creation_date' => fake()->dateTimeBetween('-1 week', 'now'),
            'barcode' => Null,
            'business_category' => fake()->randomElement(['Micro Banking', 'Branch Banking']),
        ];
    }
}
