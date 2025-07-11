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
	        ['place' =>'RESIDENCE',],
			['place' =>'ACCIDENT',],
			['place' =>'NATURAL',],
			['place' =>'FACTORY',],
			['place' =>'HOSPITAL',],
			['place' =>'OTHER',],
			['place' =>'ROAD',],
			['place' =>'HEART ATTACK',],
			['place' =>'ROAD ACCIDENT',],
			['place' =>'ON ROAD',],
			['place' =>'RAILWAY TRACK',],
			['place' =>'ABROAD',],
	    ];
        foreach ($data as $key => $value) {
        	InsurancePlaceofDeath::create($value);
        }
    }
}
