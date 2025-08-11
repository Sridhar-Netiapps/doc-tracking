<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsurancePlaceofDeath;


class InsuranceDeathPlacesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
	    $data = [
	        ['place' =>'Residence',],
			['place' =>'Accident',],
			['place' =>'Natural',],
			['place' =>'Factory',],
			['place' =>'Hospital',],
			['place' =>'Road',],
			['place' =>'Heart Attack',],
			['place' =>'Road Accident',],
			['place' =>'On Road',],
			['place' =>'Railway Track',],
			['place' =>'Abroad',],
			['place' =>'Other',],
	    ];
        foreach ($data as $key => $value) {
        	InsurancePlaceofDeath::create($value);
        }
    }
}
