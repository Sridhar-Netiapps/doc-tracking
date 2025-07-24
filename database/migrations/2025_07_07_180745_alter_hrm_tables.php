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
        Schema::table('loan_documents', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable()->after('status');
            $table->integer('dispatch_id')->nullable()->after('status');
        });
        Schema::table('gold_loan_documents', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable()->after('status');
            $table->integer('dispatch_id')->nullable()->after('status');
        });
        Schema::table('account_opening_documents', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable()->after('status');
            $table->integer('dispatch_id')->nullable()->after('status');
        });
        Schema::table('dtrf_documents', function (Blueprint $table) {
            $table->integer('vendor_id')->nullable()->after('status');
            $table->integer('dispatch_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
