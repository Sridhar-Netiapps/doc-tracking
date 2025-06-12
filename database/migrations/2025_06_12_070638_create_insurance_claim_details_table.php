<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('insurance_claim_details', function (Blueprint $table) {
            $table->id();
            $table->string('utrn')->nullable();
            $table->string('region')->nullable();
            $table->string('branch')->nullable();
            $table->string('partner')->nullable();
            $table->string('product')->nullable();
            $table->string('policy_number')->nullable();
            $table->string('cust_id')->nullable();
            $table->string('actual_id')->nullable();
            $table->string('deceased_name')->nullable();
            $table->string('mp_no')->nullable();
            $table->string('policy_covered_date')->nullable();
            $table->string('loan_tenure')->nullable();
            $table->string('policy_expiry_date')->nullable();
            $table->string('date_of_death')->nullable();
            $table->string('gender')->nullable();
            $table->string('deceased')->nullable();
            $table->string('intimation_date')->nullable();
            $table->string('age')->nullable();
            $table->string('place_of_death')->nullable();
            $table->string('cause_of_death')->nullable();
            $table->string('load_acc_id')->nullable();
            $table->string('claim_amount')->nullable();
            $table->string('dob')->nullable();
            $table->string('cliam_status')->nullable();
            $table->string('cas_status')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('relationship')->nullable();
            $table->string('nominee_number')->nullable();
            $table->string('loan_outstanding')->nullable();
            $table->string('payable_to_nominee')->nullable();
            $table->string('ack_rec_date')->nullable();
            $table->string('pkt_no')->nullable();
            $table->string('rl_status')->nullable();
            $table->string('processed_by')->nullable();
            $table->string('ho_remark')->nullable();
            $table->string('doc_rec_date')->nullable();
            $table->string('submit_to_partner_date')->nullable();
            $table->string('ho_remark2')->nullable();
            $table->string('re_submit_to_partner_date')->nullable();
            $table->string('settlement_date')->nullable();
            $table->string('neft_rejection_date')->nullable();
            $table->string('neft_rejection_reason')->nullable();
            $table->string('final_settlement_date')->nullable();
            $table->string('recovery_status')->nullable();
            $table->string('bounced_chq_no')->nullable();
            $table->string('bounced_chq_date')->nullable();
            $table->string('bounced_chq_reason')->nullable();
            $table->string('write_off_rec')->nullable();
            $table->string('write_off_status')->nullable();
            $table->string('handed_to_bh')->nullable();
            $table->string('handed_to_credit')->nullable();
            $table->string('ho_employee_id')->nullable();
            $table->string('latest_editor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_claim_details');
    }
};
