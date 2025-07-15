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
          ['partner' => 'ABSLI'],	
          ['partner' => 'Bajaj'],
          ['partner' => 'HDFC'],
          ['partner' => 'ICICI PRU'],
          ['partner' => 'KOTAK'],
          ['partner' => 'Max Life'],
          ['partner' => 'The New India Assurance Company Limited'],
          
        ];

        foreach ($data as $key => $value) {
        	InsurancePartner::create($value);
        }
    }
}
