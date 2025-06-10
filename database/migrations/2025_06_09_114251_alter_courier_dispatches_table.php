<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable()->change();
            $table->string('status')->nullable()->after('aof_ids')->change();
            $table->string('dispatch_no')->nullable()->after('dispatch_date')->change();
            $table->date('dispatch_date')->nullable()->change();
            $table->renameColumn('dispatched_by', 'created_by');
            $table->text('comments')->nullable()->after('dispatch_no');
        });
    }

    public function down(): void
    {
        Schema::table('courier_dispatches', function (Blueprint $table) {
            $table->timestamp('verified_at')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
            $table->string('dispatch_no')->nullable(false)->change();
            $table->date('dispatch_date')->nullable(false)->change();
            $table->renameColumn('created_by', 'dispatched_by');
            $table->dropColumn('comments');
        });
    }
};