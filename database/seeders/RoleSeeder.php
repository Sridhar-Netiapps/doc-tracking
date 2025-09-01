<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  
      public function run(): void
      {
    
        $data = [
        	['name' => 'South' ,'code' => '001' ],
        	['name' => 'North' ,'code' => '002' ],
        	['name' => 'East' ,'code' => '003' ],
        	['name' => 'West' ,'code' => '004' ],
			
		];


		foreach ($data as $key => $value) {
			Region::create($value);
		}
    }
}
