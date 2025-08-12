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
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->string('awb_pod')->nullable()->change();
            $table->string('mmrp_barcode')->nullable()->change();
            $table->string('courier_name')->nullable()->change();
            $table->string('courier_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->string('awb_pod')->nullable(false)->change();
            $table->string('mmrp_barcode')->nullable(false)->change();
            $table->string('courier_name')->nullable(false)->change();
            $table->string('courier_id')->nullable(false)->change();

        });
    }
};
