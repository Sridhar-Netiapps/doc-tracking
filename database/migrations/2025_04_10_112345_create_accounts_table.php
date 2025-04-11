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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('unique_ref_no')->unique();
            $table->string('region')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('cif_id')->nullable();
            $table->string('account_number')->nullable();
            $table->string('customer_name')->nullable();
            $table->date('account_creation_date')->nullable();
            $table->string('channel')->nullable();
            $table->string('business_category')->nullable();
            $table->string('barcode')->nullable();
            $table->string('type_of_account')->nullable();
            $table->string('scheme')->nullable();
            $table->string('loan_cycle')->nullable();
            $table->string('status', 100)->default('Active');
            $table->string('created_by')->default('API');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
