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
            $table->unsignedBigInteger('courier_id')->default(0);
            $table->string('courier_name');
            $table->string('loan_ids')->nullable();
            $table->string('goldloan_ids')->nullable();
            $table->string('dtrf_ids')->nullable();
            $table->string('aof_ids')->nullable();
            $table->date('dispatch_date');
            $table->unsignedBigInteger('dispatched_by');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('verified_at')->nullable()->useCurrentOnUpdate();
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
