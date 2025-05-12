<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GoldLoanDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unique_ref_no' => 'REF' . fake()->unique()->numberBetween(1000, 9999),
            'region' => fake()->randomElement(['South', 'North', 'East', 'West']),
            'branch_code' => fake()->numberBetween(1110, 1119),
            'branch_name' => fake()->city(),
            'cif_id' => 'CIF' . fake()->unique()->numberBetween(100000, 999999),
            'account_number' => 'ACC' . fake()->unique()->numberBetween(1000000, 9999999),
            'customer_name' => fake()->name(),
            'account_creation_date' => fake()->dateTimeBetween('-1 week', 'now'),
            'channel' => 'Gold Loan',
            'barcode' => NULL,
            'business_category' => fake()->word(),
        ];
    }
}
