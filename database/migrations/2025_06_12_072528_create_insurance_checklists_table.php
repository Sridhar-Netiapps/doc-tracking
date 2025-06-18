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
        Schema::create('insurance_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_claim_details_id');
            $table->string('cust_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('sent_date')->nullable();
            $table->string('deceased_name')->nullable();
            $table->json('name')->nullable();
            $table->json('name_mismatch')->nullable();
            $table->json('age')->nullable();
            $table->json('age_mismatch')->nullable();
            $table->json('customer_id')->nullable();
            $table->json('dod')->nullable();
            $table->json('is_mlc')->nullable();
            $table->json('fir_attached')->nullable();
            $table->json('death_certificate')->nullable();
            $table->json('valid_certificate')->nullable();
            $table->json('doc_bajaj')->nullable();
            $table->json('doc_death')->nullable();
            $table->json('doc_fir')->nullable();
            $table->json('doc_proof')->nullable();
            $table->json('doc_closure_request')->nullable();
            $table->json('doc_ecs')->nullable();  
            $table->json('docs_readable')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('acc_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('micr')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('branch')->nullable();
            $table->string('bo_maker_emp')->nullable();
            $table->string('bo_maker_name')->nullable();
            $table->string('bo_maker_sign')->nullable();
            $table->string('bo_maker_date')->nullable();
            $table->string('bo_checker_emp')->nullable();
            $table->string('bo_checker_name')->nullable();
            $table->string('bo_checker_sign')->nullable();
            $table->string('bo_checker_date')->nullable();
            $table->string('ho_maker_emp')->nullable();
            $table->string('ho_maker_name')->nullable();
            $table->string('ho_checker_emp')->nullable();
            $table->string('ho_checker_name')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_checklists');
    }
};
