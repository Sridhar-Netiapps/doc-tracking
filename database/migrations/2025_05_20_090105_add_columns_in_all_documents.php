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
            $table->text('reason')->nullable();
        });
        Schema::table('gold_loan_documents', function (Blueprint $table) {
            $table->text('reason')->nullable();
        });
        Schema::table('account_opening_documents', function (Blueprint $table) {
            $table->text('reason')->nullable();
        });
        Schema::table('dtrf_documents', function (Blueprint $table) {
            $table->text('reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_documents', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
        Schema::table('gold_loan_documents', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
        Schema::table('account_opening_documents', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
        Schema::table('dtrf_documents', function (Blueprint $table) {
            $table->dropColumn('reason');
        });
    }
};
