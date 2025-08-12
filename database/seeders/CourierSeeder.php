<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Courier;
use Str;


class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $couriers = [
            ['name' => 'Airways'],
            ['name' => 'Anjani'],
            ['name' => 'Blue Dart'],
            ['name' => 'Delhivery Courier'],
            ['name' => 'DHL Courier'],
            ['name' => 'DTDC'],
            ['name' => 'France Express'],
            ['name' => 'GMS'],
            ['name' => 'India Postal'],
            ['name' => 'Jet Line'],
            ['name' => 'Mahaveer'],
            ['name' => 'Mark'],
            ['name' => 'Maruti air'],
            ['name' => 'Megacity'],
            ['name' => 'Professional'],
            ['name' => 'S T Courier'],
            ['name' => 'Shree Maruti'],
            ['name' => 'Sky King'],
            ['name' => 'Tirupathi Courier'],
            ['name' => 'Track on'],
            ['name' => 'Others']
        ];

        foreach ($couriers as $courier) {
            Courier::insert([
                'courier_id' => Str::slug($courier['name'], '_'),
                'name' => $courier['name'],
                'number' => '9876543210',
                'address' => 'Bangalore'
            ]);
        }
    }
}
