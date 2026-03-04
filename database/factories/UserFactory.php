<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $regions = [
            'South' => 1,
            'North' => 2,
            'East'  => 3,
            'West'  => 4,
        ];
    
        $regionName = $this->faker->randomElement(array_keys($regions));
        $gender = $this->faker->randomElement(['Male', 'Female']);
        return [
            'first_name'        => $this->faker->firstName($gender),
            'last_name'         => $this->faker->lastName,
            'middle_name'       => $this->faker->optional()->firstName,
            'employee_id'       => strtoupper('NET' . $this->faker->unique()->numberBetween(1000, 9999)),
            'email'             => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password'          => Hash::make('password'), // Use bcrypt or Hash::make
            'dor'               => NULL, // Date of resignation
            'doj'               => $this->faker->dateTimeBetween('-10 years', '-6 years'), // Date of joining
            'mobile_number'     => $this->faker->numerify('9#########'),
            'dob'               => $this->faker->dateTimeBetween('-30 years', '-22 years'),
            'gender'            => $gender,
            'remember_token'    => Str::random(10),
            'status'            => $this->faker->randomElement(['active', 'inactive']),
            'created_by'        => 1,
            'created_at'        => now(),
            'updated_at'        => NULL,
            'branch_id'         => $regions[$regionName].$this->faker->numberBetween(111, 119),
            'region'            => 'South',
            'region_id'         => 1,
            // 'designation_id'    => 0,
            // 'department_id'     => 0,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
