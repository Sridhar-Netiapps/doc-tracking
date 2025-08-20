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
        	['product' => 'BAJAJ-Micro banking' ,'folder_name' => 'BAJAJ' ,'type' => 'MB' ,'partner_id' => '2'],
        	['product' => 'BAJAJ-Vehicle' ,'folder_name' => 'BA','type' => 'NMB' ,'partner_id' => '2'],
			['product' => 'BAJAJ-KPC' , 'folder_name' => 'BAJAJ','type' => 'MB' ,'partner_id' => '2'],
			['product' => 'ABSLI-Microbanking' , 'folder_name' => 'ABSLI','type' => 'MB' ,'partner_id' => '1'],
			['product' => 'ABSLI-Housing' , 'folder_name' => 'AB','type' => 'NMB' ,'partner_id' => '1'],
			['product' => 'ABSLI-MLAP' , 'folder_name' => 'AB','type' => 'NMB' ,'partner_id' => '1'],
			['product' => 'ABSLI-Vehicle' , 'folder_name' => 'AB','type' => 'NMB' ,'partner_id' => '1'],
			['product' => 'ABSLI-MSME' , 'folder_name' => 'AB','type' => 'NMB','partner_id' => '1' ],
			['product' => 'HDFC Microbanking' , 'folder_name' => 'HDFC','type' => 'MB' ,'partner_id' => '3'],
			['product' => 'HDFC-MSME' , 'folder_name' => 'HD','type' => 'NMB' ,'partner_id' => '3'],
			['product' => 'HDFC-AGL' , 'folder_name' => 'HDFC','type' => 'MB' ,'partner_id' => '3'],
			['product' => 'HDFC-PL' , 'folder_name' => 'HD','type' => 'NMB' ,'partner_id' => '3'],
			['product' => 'MAX LIFE-Micro banking' , 'folder_name' => 'Maxlife','type' => 'MB' ,'partner_id' => '6'],
			['product' => 'MAX LIFE-Housing' , 'folder_name' => 'ML','type' => 'NMB'  ,'partner_id' => '6'],
			['product' => 'MAX LIFE-MSME' , 'folder_name' => 'ML','type' => 'NMB'  ,'partner_id' => '6'],
			['product' => 'MAX LIFE-KPC' , 'folder_name' => 'ML','type' => 'NMB'  ,'partner_id' => '6'],
			['product' => 'ICICI PRU-Housing' , 'folder_name' => 'IC','type' => 'NMB'  ,'partner_id' => '4'],
			['product' => 'KOTAK-Housing' , 'folder_name' => 'KM','type' => 'NMB'  ,'partner_id' => '5'],
			['product' => 'RuPay Debit Card' , 'folder_name' => '','type' => 'NMB'  ,'partner_id' => '7'],
		];


		foreach ($data as $key => $value) {
			InsuranceProduct::create($value);
		}	

	    		
    }
}
