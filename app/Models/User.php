<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable
{
    use HasRoles, HasPermissions, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'first_name', 'employee_id', 'last_name', 'middle_name', 'email', 'password', 'dor', 'doj', 'mobile_number', 'dob', 'gender', 'status', 'branch_id', 'region', 'region_id', 'designation_id', 'department_id','ins_user'
    // ];
     protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'password', 'employee_id',
        'region', 'branch_id', 'email', 'gender', 'dob', 'status',
        'mobile_number', 'doj', 'dor', 'employee_type', 'current_designation',
        'grade', 'confirmation_status', 'date_of_confirmation',
        'current_location_type', 'direct_manager_name', 'direct_manager_emp_id',
        'direct_manager_email', 'office_location', 'current_department',
        'top_department', 'department_hierarchy_1_name', 'designation_id', 'department_id',
        'department_hierarchy_2_name', 'department_hierarchy_3_name',
        'functional_head', 'functional_head_emp_id', 'work_flow_role',
        'prac_designation', 'prac_role', 'pac_designation', 'pac_role', 'ins_user','module_role','doc_user','creator'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hrmData(){
        return $this->hasOne(HRMData::class,'employee_id','employee_id');
    }

    /*public function myrole(){
        return $this->hasOne()
    }*/
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function lastLogin()
    {
        return $this->hasOne(ActivityLog::class, 'user_id')
            ->where('route', 'login');
    }
}

