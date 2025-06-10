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
            ['relationship' =>'applicant',],
			['relationship' =>'BROTHER-IN-LAW',],
			['relationship' =>'MOTHER IN LAW',],
			['relationship' =>'cousin',],
			['relationship' =>'spouse',],
			['relationship' =>'Daughter',],
			['relationship' =>'Daughter-in-law',],
			['relationship' =>'father',],
			['relationship' =>'Father-in-law',],
			['relationship' =>'GUARDIAN',],
			['relationship' =>'Husband',],
			['relationship' =>'Mother',],
			['relationship' =>'MOTHER-IN-LAW',],
			['relationship' =>'Sister',],
			['relationship' =>'Sister-In-Law',],
			['relationship' =>'son',],
			['relationship' =>'SON-IN-LAW',],
			['relationship' =>'wife',],
			['relationship' =>'Brother',],
			['relationship' =>'Grandson',],
			['relationship' =>'Grand daughter',],
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
