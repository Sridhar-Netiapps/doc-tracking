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
            $table->string('glow_application_id')->after('channel');
            $table->string('loan_amount')->after('glow_application_id');
        });
        Schema::table('gold_loan_documents', function (Blueprint $table) {
            $table->string('loan_amount')->after('channel');
        });
        Schema::table('account_opening_documents', function (Blueprint $table) {
            $table->string('pgk_no')->after('channel');
        });
    }

    public function down(): void
    {
        //
    }
};
