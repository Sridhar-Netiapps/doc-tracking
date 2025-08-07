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
        Schema::create('insurance_documents', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_claim_details_id');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('filepath');
            $table->string('status');
            $table->string('creator');
            $table->string('updator');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_documents');
    }
};
