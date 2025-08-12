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
        Schema::create('vendor_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document_unique_no')->nullable();
            $table->string('dispatch_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('category_of_document')->nullable();
            $table->string('work_order_no')->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('vendor_movement_date')->nullable();
            $table->string('file_barcode')->nullable();
            $table->string('box_barcode')->nullable();
            $table->date('date_added_to_vendor')->nullable();
            $table->string('status', 100)->default(8);
            $table->string('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->string('awb_pod')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_documents');
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->string('awb_pod')->nullable(false)->change();
        });
    }
};
