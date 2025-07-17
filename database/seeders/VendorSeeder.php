<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            ['name' => 'Iron Mountain India Private Limited	'],
            ['name' => 'Writers Business Services Private Limited	']
        ];

        foreach ($vendors as $vendor) {
            Vendor::insert([
                'name' => $vendor['name'],
                'location' => 'Bangalore',
                'created_by' => '0'
            ]);
        }
    }
}
