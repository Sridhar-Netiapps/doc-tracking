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
        	['cause' =>'Accident',],
			['cause' =>'Accident(Burning)',],
			['cause' =>'Cancer',],
			['cause' =>'Sudden Death',],
			['cause' =>'Murder',],
			['cause' =>'C.R.Failure',],
			['cause' =>'Stove BurstT',],
			['cause' =>'Snake Bite',],
			['cause' =>'Kidney Failure',],
			['cause' =>'Residence',],
			['cause' =>'Head Injury',],
			['cause' =>'Natural',],
			['cause' =>'Heart Attack',],
			['cause' =>'Illness',],
			['cause' =>'Burn',],
			['cause' =>'Stroke',],
			['cause' =>'Electshock',],
			['cause' =>'Covid-19',],
			['cause' =>'Suicide',],
			['cause' =>'Drowning']
        ];
        
        foreach ($data as $key => $value) {
        	InsuranceCauseOfDeath::create($value);
        }
        
    }
}
