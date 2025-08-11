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
        Schema::create('h_r_m_data', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('gender');
            $table->string('dob');
            $table->string('office_mobile');
            $table->string('office_email');
            $table->string('employee_type');
            $table->string('employee_status');
            $table->string('current_designation');
            $table->string('grade');
            $table->string('doj');
            $table->string('doe');
            $table->string('confirmation_status');
            $table->string('date_of_confirmation');
            $table->string('current_location_type');
            $table->string('direct_manager_name');
            $table->string('direct_manager_emp_id');
            $table->string('direct_manager_email');
            $table->string('office_location');
            $table->string('office_loc_code');
            $table->string('office_region');
            $table->string('current_department');
            $table->string('top_department');
            $table->string('department_hierarchy_1_name')->nullable();
            $table->string('department_hierarchy_2_name')->nullable();
            $table->string('department_hierarchy_3_name')->nullable();
            $table->string('functional_head')->nullable();
            $table->string('functional_head_emp_id')->nullable();
            $table->string('work_flow_role')->nullable();
            $table->string('prac_designation')->nullable();
            $table->string('prac_role')->nullable();
            $table->string('pac_designation')->nullable();
            $table->string('pac_role')->nullable();
            $table->date('load_date')->default(now());
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('h_r_m_data');
    }
};
