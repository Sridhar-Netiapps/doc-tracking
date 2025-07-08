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
        Schema::create('hrm_loan_documents', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('branch_code');
            $table->string('branch_name');
            $table->string('cif_id');
            $table->string('account_number');
            $table->string('loan_cycle');
            $table->string('customer_name');
            $table->date('account_creation_date');
            $table->string('channel');
            $table->string('glow_application_id');
            $table->string('loan_amount');
            $table->string('loan_disbursement_type');
            $table->string('business_category');
            $table->string('barcode')->nullable();
            $table->date('added_at');
            $table->string('status')->default('migrated');
            $table->string('created_by')->default('IT');
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrm_loan_documents');
    }
};
