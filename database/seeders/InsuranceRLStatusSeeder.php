<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceRequestLetterStatus;

class InsuranceRLStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	['rl_status' => 'NA'],
        	['rl_status' => 'PDC Process'],
        	['rl_status' => 'RL Process']
        ];
        

        foreach ($data as $key => $value) {
        	InsuranceRequestLetterStatus::create($value);
        }
    }
}
