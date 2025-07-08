<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LoanDocument;
use App\Models\HrmLoanDocument;
use App\Models\GoldLoanDocument;
use App\Models\HrmGoldLoanDocument;
use App\Models\DtrfDocument;
use App\Models\HrmDtrfDocument;
use App\Models\AccountOpeningDocument;
use App\Models\HrmAccountOpeningDocument;

class DocumentSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $table = (string) ($this->command->ask('Where We Need to Insert Sample Documents?', 'HRM'));
        $loanCount = (int) ($this->command->ask('How many Loan Documents?', 100));
        $goldCount = (int) ($this->command->ask('How many Gold Loan Documents?', 100));
        $dtrfCount = (int) ($this->command->ask('How many DTRF Documents?', 100));
        $aofCount = (int) ($this->command->ask('How many AOF Documents?', 100));

        // if($table == 'HRM'){
            HrmLoanDocument::factory()->count($loanCount)->create();
            HrmGoldLoanDocument::factory()->count($goldCount)->create();
            HrmDtrfDocument::factory()->count($dtrfCount)->create();
            HrmAccountOpeningDocument::factory()->count($aofCount)->create();
        // }
        // else{
        //     LoanDocument::factory()->count($loanCount)->create();
        //     GoldLoanDocument::factory()->count($goldCount)->create();
        //     DtrfDocument::factory()->count($dtrfCount)->create();
        //     AccountOpeningDocument::factory()->count($aofCount)->create();
        // }        
    }
}
