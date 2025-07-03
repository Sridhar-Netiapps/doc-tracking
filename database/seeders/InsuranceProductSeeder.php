<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceProduct;

class InsuranceProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	['product' => 'Group Term Loan'],
        	['product' => 'KOTAK'],
			['product' => 'BSLI'],
			['product' => 'BSLI-GTI'],
			['product' => 'BSLI-GCPP'],
			['product' => 'BSLI-Housing'],
			['product' => 'HDFC'],
			['product' => 'HDFC-GTI'],
			['product' => 'HDFC-GCPP'],
			['product' => 'HDFC-MSE'],
			['product' => 'KOTAK-MSE'],
			['product' => 'KOTAK-Housing-'],
			['product' => 'HDFC-AGL'],
			['product' => 'BSLI-Vehicle-2'],
			['product' => 'HDFC-PL'],
			['product' => 'BSLI-Vehicle-3'],
			['product' => 'BSLI-Vehicle-4'],
			['product' => 'BSLI-MSE'],
			['product' => 'KPC-Bajaj'],
			['product' => 'KCC-Bajaj'],
			['product' => 'MAX LIFE'],
			['product' => 'ICICI PRU-HL'],
			['product' => 'MAX LIFE-MSE'],
			['product' => 'Vikas Loan(MLAP)'],
			['product' => 'MAX LIFE KPC'],
			['product' => 'BAJAJ-Vehicle']
		];


		foreach ($data as $key => $value) {
			InsuranceProduct::create($value);
		}	

	    		
    }
}
