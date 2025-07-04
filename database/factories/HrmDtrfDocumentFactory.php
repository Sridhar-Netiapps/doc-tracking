<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HrmDtrfDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // 'unique_ref_no' => 'REF' . fake()->unique()->numberBetween(1000, 9999),
            'region' => 'South',
            'branch_code' => fake()->numberBetween(1110, 1119),
            'branch_name' => fake()->city(),
            'account_creation_date' => fake()->dateTimeBetween('-1 week', 'now'),
            'barcode' => Null,
            'business_category' => fake()->randomElement(['Micro Banking', 'Branch Banking']),
        ];
    }
}
