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
        Schema::create('courier_dispatches', function (Blueprint $table) {
            $table->id();
            $table->string('awb_pod');
            $table->string('courier_id');
            $table->string('courier_name');
            $table->date('dispatch_date');
            $table->string('loan_ids');
            $table->string('goldloan_ids');
            $table->string('dtrf_ids');
            $table->string('aof_ids');
            $table->unsignedBigInteger('dispatched_by'); // User ID
            $table->string('created_by')->default('1');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courier_dispatches');
    }
};
