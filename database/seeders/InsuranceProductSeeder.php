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
        	['product' => 'BAJAJ-Micro banking' ,'folder_name' => 'BAJAJ' ,'type' => 'MB' ],
        	['product' => 'BAJAJ-Vehicle' ,'folder_name' => 'BA','type' => 'NMB' ],
			['product' => 'BAJAJ-KPC' , 'folder_name' => 'BAJAJ','type' => 'MB' ],
			['product' => 'ABSLI-Microbanking' , 'folder_name' => 'ABSLI','type' => 'MB' ],
			['product' => 'ABSLI-Housing' , 'folder_name' => 'AB','type' => 'NMB' ],
			['product' => 'ABSLI-MLAP' , 'folder_name' => 'AB','type' => 'NMB' ],
			['product' => 'ABSLI-Vehicle' , 'folder_name' => 'AB','type' => 'NMB' ],
			['product' => 'ABSLI-MSME' , 'folder_name' => 'AB','type' => 'NMB' ],
			['product' => 'HDFC Microbanking' , 'folder_name' => 'HDFC','type' => 'MB' ],
			['product' => 'HDFC-MSME' , 'folder_name' => 'HD','type' => 'NMB' ],
			['product' => 'HDFC-AGL' , 'folder_name' => 'HDFC','type' => 'MB' ],
			['product' => 'HDFC-PL' , 'folder_name' => 'HD','type' => 'NMB' ],
			['product' => 'MAX LIFE-Micro banking' , 'folder_name' => 'Maxlife','type' => 'MB' ],
			['product' => 'MAX LIFE-Housing' , 'folder_name' => 'ML','type' => 'NMB' ],
			['product' => 'MAX LIFE-MSME' , 'folder_name' => 'ML','type' => 'NMB' ],
			['product' => 'MAX LIFE-KPC' , 'folder_name' => 'ML','type' => 'NMB' ],
			['product' => 'ICICI PRU-Housing' , 'folder_name' => 'IC','type' => 'NMB' ],
			['product' => 'KOTAK-Housing' , 'folder_name' => 'KM','type' => 'NMB' ],
			['product' => 'RuPay Debit Card' , 'folder_name' => '','type' => 'NMB' ],
		];


		foreach ($data as $key => $value) {
			InsuranceProduct::create($value);
		}	

	    		
    }
}
