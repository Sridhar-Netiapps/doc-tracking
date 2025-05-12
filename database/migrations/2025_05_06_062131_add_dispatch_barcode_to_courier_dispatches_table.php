<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->string('mmrp_barcode')->after('courier_id');
            $table->string('branch_code')->after('courier_id');
            $table->string('region')->after('courier_id');
        });
    }

    public function down(): void
    {
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->dropColumn('mmrp_barcode');
            $table->dropColumn('branch_code');
            $table->dropColumn('region');
        });
    }
};
