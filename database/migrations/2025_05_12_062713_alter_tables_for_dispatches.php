<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('region_id')->default(1);
        });
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->renameColumn('region','region_id');
            $table->string('dispatch_no')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('region_id');
        });
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->renameColumn('region_id','region');
            $table->dropColumn('dispatch_no');
        });
    }
};