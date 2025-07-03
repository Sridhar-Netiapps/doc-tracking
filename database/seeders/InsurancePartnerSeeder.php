<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsurancePartner;

class InsurancePartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
          ['partner' => 'Bajaj'],	
          ['partner' => 'Birla'],
          ['partner' => 'HDFC'],
          ['partner' => 'Max Life'],
          
        ];

        foreach ($data as $key => $value) {
        	InsurancePartner::create($value);
        }
    }
}
