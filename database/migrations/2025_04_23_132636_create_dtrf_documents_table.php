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
        Schema::create('dtrf_documents', function (Blueprint $table) {
            $table->id();
            $table->string('unique_ref_no');
            $table->string('region');
            $table->string('branch_code');
            $table->string('branch_name');
            $table->date('dtr_file_date');
            $table->string('barcode')->nullable();
            $table->string('business_category');
            $table->string('status', 100)->default('Pending');
            $table->string('created_by')->default(1);
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
        Schema::dropIfExists('dtrf_documents');
    }
};
