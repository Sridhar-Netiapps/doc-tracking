<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dtrf_documents', function (Blueprint $table) {
            $table->renameColumn('dtr_file_date','account_creation_date');
        });
    }

    public function down(): void
    {
        Schema::table('dtrf_documents', function (Blueprint $table) {
            $table->renameColumn('account_creation_date','dtr_file_date');
        });
    }
};