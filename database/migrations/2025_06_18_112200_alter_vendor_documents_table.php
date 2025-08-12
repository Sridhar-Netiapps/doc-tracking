<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loan_documents', function (Blueprint $table) {
            // $table->integer('vendor_id')->nullable()->after('status');
            // $table->integer('dispatch_id')->nullable()->after('status');
            $table->string('lot_no')->nullable();
            $table->string('category_of_document')->nullable();
            $table->string('work_order_no')->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('vendor_movement_date')->nullable();
            $table->string('file_barcode')->nullable();
            $table->string('box_barcode')->nullable();
            $table->date('date_added_to_vendor')->nullable();
        });
        Schema::table('gold_loan_documents', function (Blueprint $table) {
            // $table->integer('vendor_id')->nullable()->after('status');
            // $table->integer('dispatch_id')->nullable()->after('status');
            $table->string('lot_no')->nullable();
            $table->string('category_of_document')->nullable();
            $table->string('work_order_no')->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('vendor_movement_date')->nullable();
            $table->string('file_barcode')->nullable();
            $table->string('box_barcode')->nullable();
            $table->date('date_added_to_vendor')->nullable();
        });
        Schema::table('account_opening_documents', function (Blueprint $table) {
            // $table->integer('vendor_id')->nullable()->after('status');
            // $table->integer('dispatch_id')->nullable()->after('status');
            $table->string('lot_no')->nullable();
            $table->string('category_of_document')->nullable();
            $table->string('work_order_no')->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('vendor_movement_date')->nullable();
            $table->string('file_barcode')->nullable();
            $table->string('box_barcode')->nullable();
            $table->date('date_added_to_vendor')->nullable();
        });
        Schema::table('dtrf_documents', function (Blueprint $table) {
            // $table->integer('vendor_id')->nullable()->after('status');
            // $table->integer('dispatch_id')->nullable()->after('status');
            $table->string('lot_no')->nullable();
            $table->string('category_of_document')->nullable();
            $table->string('work_order_no')->nullable();
            $table->string('vendor_name')->nullable();
            $table->date('vendor_movement_date')->nullable();
            $table->string('file_barcode')->nullable();
            $table->string('box_barcode')->nullable();
            $table->date('date_added_to_vendor')->nullable();
        });
    }

    public function down(): void
    {
        //
    }
};
