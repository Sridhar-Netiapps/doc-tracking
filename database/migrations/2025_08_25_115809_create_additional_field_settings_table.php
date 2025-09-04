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
        Schema::create('additional_field_settings', function (Blueprint $table) {
            $table->id();
            $table->string('field_name');
            $table->string('field_type');
            $table->string('allowed_chars');
            $table->string('module')->nullable();
            $table->string('creator')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_field_settings');
    }
};
