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
        Schema::create('insurance_nominee_details', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_claim_details_id')->nullable();
            $table->string('nominee_name_bank')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('acc_number')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('spdc_bank_name')->nullable();
            $table->string('spdc_chk_no')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('pod_no')->nullable();
            $table->string('cheq_sent_date')->nullable();
            $table->string('bo_remarks')->nullable();
            $table->string('bo_maker')->nullable();
            $table->string('bo_checker')->nullable();
            $table->string('bo_employee_id')->nullable();
            $table->string('latest_editor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_nominee_details');
    }
};
