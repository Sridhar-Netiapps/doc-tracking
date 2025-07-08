<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProcessStatus;

class ProcessStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $process_status = [
            ['name' => 'Pending'],
            ['name' => 'Selected'],
            ['name' => 'Awaiting Checker Approval'],
            ['name' => 'Dispatched'],
            ['name' => 'Recieved'],
            ['name' => 'Rejected'],
            ['name' => 'Received With Query'],
            ['name' => 'IN'],
            ['name' => 'OUT'],
            ['name' => 'Permount'],
            ['name' => 'Destroyed']
        ];

        foreach ($process_status as $status) {
            ProcessStatus::table('process_status')->insert([
                'name' => $status['name'],
            ]);
        }
    }
}
