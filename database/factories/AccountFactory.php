<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unique_ref_no' => 'REF' . $this->faker->unique()->numberBetween(1000, 9999),
            'region' => $this->faker->randomElement(['South', 'North', 'East', 'West']),
            'branch_code' => 'BR' . $this->faker->numberBetween(100, 999),
            'branch_name' => $this->faker->city,
            'cif_id' => 'CIF' . $this->faker->unique()->numberBetween(100000, 999999),
            'account_number' => 'ACC' . $this->faker->unique()->numberBetween(1000000, 9999999),
            'customer_name' => $this->faker->name,
            'account_creation_date' => $this->faker->dateTimeBetween('-2 week', 'now'),
            'channel' => $this->faker->randomElement(['GL', 'IL']),
            'business_category' => $this->faker->word,
            'barcode' => 'BAR' . $this->faker->numberBetween(1000, 9999),
            'type_of_account' => $this->faker->randomElement(['Savings', 'Current','Loan']),
            'scheme' => $this->faker->word,
            'loan_cycle' => 'Cycle ' . $this->faker->numberBetween(1, 5),
        ];
    }
}
