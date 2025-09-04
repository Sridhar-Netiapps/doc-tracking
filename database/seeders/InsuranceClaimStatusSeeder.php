<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InsuranceClaimStatus;

class InsuranceClaimStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        	['claim_status' => 'Pending From Branch'],
        	['claim_status' => 'Document Sent to HO to Process'],
			['claim_status' => 'Docs Received, Pending to Process'],
			['claim_status' => 'Pending from Branch-Require Additional Documents'],
			['claim_status' => 'Pending from Insurance Company'],
			['claim_status' => 'Pending from Branch-Requirements Raised by Ins Partner'],
			['claim_status' => 'Completed'],
			['claim_status' => 'NEFT Rejected'],
			['claim_status' => 'Need Nominee Bank AC for Re-issuance'],
            ['claim_status' => 'Nominee Bank AC Received For Re-issue'],
            ['claim_status' => 'NEFT Rejected-Sent for Re-processing'],
			['claim_status' => 'NEFT Re-processed'],
			['claim_status' => 'Not Eligible'],
			['claim_status' => 'Not Eligible-Having outstanding'],
			['claim_status' => 'Not Eligible-Not Insured'],
			['claim_status' => 'Rejected'],
			['claim_status' => 'Removed'],
			['claim_status' => 'Loan Written off'],
			['claim_status' => 'Pending From RO-Need Declaration'],
			['claim_status' => 'Pending From RO-Need DOGH'],
			['claim_status' => 'Write off Form Received Pending to Process'],
			['claim_status' => 'Write off Rejected-Form Incomplete'],
			['claim_status' => 'Writeoff Form Received-Loan Already Closed'],
			['claim_status' => 'Write-off Form Handed Over to Business'],
			['claim_status' => 'Pending with Insurance Operations'],

			
        ];

        foreach ($data as $key => $value) {
			InsuranceClaimStatus::create($value);
		}
    }
}
