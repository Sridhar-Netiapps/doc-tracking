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
        Schema::create('account_opening_documents', function (Blueprint $table) {
            $table->id();
            $table->string('unique_ref_no');
            $table->string('region');
            $table->string('branch_code');
            $table->string('branch_name');
            $table->string('cif_id');
            $table->string('account_number');
            $table->string('customer_name');
            $table->date('account_creation_date');
            $table->string('scheme');
            $table->string('channel');
            $table->string('barcode')->nullable();
            $table->string('type_of_account_opening');
            $table->string('business_category');
            $table->string('status', 100)->default('Inserted');
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
        Schema::dropIfExists('account_opening_documents');
    }
};
