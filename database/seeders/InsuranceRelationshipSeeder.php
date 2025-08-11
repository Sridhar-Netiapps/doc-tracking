<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceRelationship;

class InsuranceRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['relationship' =>'Applicant',],
			['relationship' =>'Brother-In-Law',],
			['relationship' =>'Mother-In-Law',],	
			['relationship' =>'Cousin',],
			['relationship' =>'Spouse',],
			['relationship' =>'Daughter',],
			['relationship' =>'Daughter-In-Law',],
			['relationship' =>'Father',],
			['relationship' =>'Father-In-Law',],
			['relationship' =>'Guardian',],
			['relationship' =>'Husband',],
			['relationship' =>'Mother',],
			['relationship' =>'Sister',],
			['relationship' =>'Sister-In-Law',],
			['relationship' =>'son',],
			['relationship' =>'Son-In-Law',],
			['relationship' =>'wife',],
			['relationship' =>'Brother',],
			['relationship' =>'Grandson',],
			['relationship' =>'Grand Daughter',],
			['relationship' =>'Aunty',],
			['relationship' =>'Nephew',],
			['relationship' =>'Grand Mother',],
			['relationship' =>'Grand Father',],
			['relationship' =>'Niece',],
			['relationship' =>'Uncle',],
        ];

        foreach ($data as $key => $value) {
        	InsuranceRelationship::create($value);
        }
    }
}
