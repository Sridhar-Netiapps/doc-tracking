<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoanDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unique_ref_no' => 'REF' . $this->faker->unique()->numberBetween(1000, 9999),
            'region' => $this->faker->randomElement(['South', 'North', 'East', 'West']),
            'branch_code' => $this->faker->numberBetween(1110, 1119),
            'branch_name' => $this->faker->city,
            'cif_id' => 'CIF' . $this->faker->unique()->numberBetween(100000, 999999),
            'account_number' => 'ACC' . $this->faker->unique()->numberBetween(1000000, 9999999),
            'loan_cycle' => $this->faker->numberBetween(1, 9),
            'customer_name' => $this->faker->name,
            'account_creation_date' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'channel' => $this->faker->randomElement(['GL', 'IL']),
            'barcode' => NULL,
            'loan_disbursement_type' => fake()->randomElement(['Esign', 'Manual']),
            'business_category' => fake()->randomElement(['Micro Banking', 'Branch Banking']),
        ];
    }
}