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
        Schema::create('process_status', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the status
            $table->boolean('status')->default(true); // Active or inactive
            $table->integer('created_by'); // User who created the entry
            $table->integer('updated_by')->nullable(); // User who updated the entry
            $table->timestamps(); // created_at and updated_at
            $table->integer('deleted_by')->nullable(); // User who deleted the entry
            $table->softDeletes(); // Soft delete column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_status');
    }
};
