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
        Schema::create('hrm_account_opening_documents', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('branch_code');
            $table->string('branch_name');
            $table->string('cif_id');
            $table->string('account_number');
            $table->string('customer_name');
            $table->date('account_creation_date');
            $table->string('scheme');
            $table->string('channel');
            $table->string('pgk_no');
            $table->string('barcode');
            $table->string('account_opening_type');
            $table->string('business_category');
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
        Schema::dropIfExists('hrm_account_opening_documents');
    }
};
