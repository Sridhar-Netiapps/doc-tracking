<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceCauseOfDeath;

class InsuranceCauseofDeathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	['cause' =>'ACCIDENT',],
			['cause' =>'ACCIDENT(BURNING)',],
			['cause' =>'CANCER',],
			['cause' =>'SUDDEN DEATH',],
			['cause' =>'MURDER',],
			['cause' =>'C.R. FAILURE',],
			['cause' =>'STOVE BURST',],
			['cause' =>'SNAKE BITE',],
			['cause' =>'KIDNEY FAILURE',],
			['cause' =>'RESIDENCE',],
			['cause' =>'HEAD INJURY',],
			['cause' =>'NATURAL',],
			['cause' =>'HEART ATTACK',],
			['cause' =>'ILLNESS',],
			['cause' =>'BURN',],
			['cause' =>'C R FAILURE',],
			['cause' =>'STROKE',],
			['cause' =>'ELECTSHOCK',],
			['cause' =>'SNAKEBITE',],
			['cause' =>'COVID-19',],
			['cause' =>'SUICIDE',],
			['cause' =>'DROWNING']
        ];
        
        foreach ($data as $key => $value) {
        	InsuranceCauseOfDeath::create($value);
        }
        
    }
}
