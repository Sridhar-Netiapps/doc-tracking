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
        Schema::create('additional_fields', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_claim_details_id');
            $table->string('additional_field_settings_id');
            $table->string('param_name');
            $table->string('param_value')->nullable();
            $table->string('creator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_fields');
    }
};
