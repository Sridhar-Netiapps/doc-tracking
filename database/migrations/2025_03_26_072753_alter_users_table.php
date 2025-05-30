<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name','first_name');
            $table->string('employee_id')->after('first_name');
            $table->string('last_name')->after('first_name');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('status')->after('remember_token');
            $table->date('dor')->nullable()->after('password');
            $table->date('doj')->nullable()->after('password');
            $table->string('mobile_number')->after('password');
            $table->date('dob')->nullable()->after('password'); // Ensure this is nullable
            $table->string('gender')->after('password');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->after('status');
            $table->foreignId('designation_id')->constrained()->onDelete('cascade')->after('status');
            $table->foreignId('department_id')->constrained()->onDelete('cascade')->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the newly added columns
            $table->dropColumn([
                'first_name', 
                'middle_name', 
                'last_name', 
                'employee_id',
                'gender', 
                'dob', 
                'status', 
                'mobile_number', 
                'branch_id', 
                'designation_id', 
                'doj', 
                'dor', 
                'department_id',
            ]);
        });
    }
};
