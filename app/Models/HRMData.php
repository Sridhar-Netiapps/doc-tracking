<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HRMData extends Model
{
    protected $fillable = [
            'employee_id',
            'first_name',
            'last_name',
            'middle_name',
            'gender',
            'dob',
            'office_mobile',
            'office_email',
            'employee_type',
            'employee_status',
            'current_designation',
            'grade',
            'doj',
            'doe',
            'confirmation_status',
            'date_of_confirmation',
            'current_location_type',
            'direct_manager_name',
            'direct_manager_emp_id',
            'direct_manager_email',
            'office_location',
            'office_loc_code',
            'office_region',
            'current_department',
            'top_department',
            'department_hierarchy_1_name',
            'department_hierarchy_2_name',
            'department_hierarchy_3_name',
            'functional_head',
            'functional_head_emp_id',
            'work_flow_role',
            'prac_designation',
            'prac_role',
            'pac_designation',
            'pac_role',
            'load_date',
            'status',
        ];
}
