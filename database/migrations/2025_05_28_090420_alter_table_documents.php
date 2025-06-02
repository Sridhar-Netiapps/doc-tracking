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
            $table->string('status')->default(1)->change();
        });
        Schema::table('gold_loan_documents', function (Blueprint $table) {
            $table->string('status')->default(1)->change();
        });
        Schema::table('account_opening_documents', function (Blueprint $table) {
            $table->string('status')->default(1)->change();
        });
        Schema::table('dtrf_documents', function (Blueprint $table) {
            $table->string('status')->default(1)->change();
        });
    }

    public function down(): void
    {
        //
    }
};
