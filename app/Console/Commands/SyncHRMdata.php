<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HRMData;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Log;

class SyncHRMdata extends Command
{
     /**
          * The name and signature of the console command.
          *
          * @var string
          */
     protected $signature = 'app:sync-h-r-mdata';

     /**
          * The console command description.
          *
          * @var string
          */
     protected $description = 'Command description';

     /**
          * Execute the console command.
          */
     public function handle()
     {

          $currentDateTime = Carbon::today();
          $currentDate = $currentDateTime->format('Y-m-d');
          
          $empIds = User::pluck('employee_id')->toArray();

          $hrmData = HRMData::whereIn('employee_id', $empIds)->where('load_date',$currentDate)->get();
        
          if($hrmData){
               foreach($hrmData as $key => $value){      
                    $user = User::where('employee_id',$value->employee_id)->first();
                    $branchData = $value->office_loc_code;
                    $branch_data = explode('-',$branchData);
                    $region_id = '';
                    $is_ins_user = '0';

                    $role = 'branch-user';

                    $admin_designations = ["National Manager-Banking Operations",
                    // "Regional Operations Manager" 
                    ];

                    $super_admin_designations = ["Specialist-IDAM",
                    "Systems Analyst",
                    "Engineering Graduate Trainee",
                    "Manager-Identity Management",
                    "Officer-IT Software Support" ];

                    $ho_designations = ["Chief of Staff-Branch Banking",
                         "National Manager-Branch Banking",
                         "National Manager-Branch Banking Operations",
                         "National Manager- Business Correspondents and Partnership",
                         "National Manager- Family Banking Digital Payments and Lending",
                         "National Manager-Individual Loans",
                         "National Manger-Group loans",
                         "Product Manager-IL Unsecured",
                         "Product Manager-Micro Banking",
                         "Product Manager-Microbanking",
                         "Head Of Operations",
                         "Lead Micro Banking Operations",
                         "Lead-Centralised Banking Operations",
                         "Manager-Centralized Banking Operations",
                         // "National Manager-Banking Operations",
                         "National Manager-Housing Loans PL and VL Operations",
                         "National Manager-MSME Operations and FIG; Operations",
                         "National Manager-Payments and Settlements" ];

                    $ro_supervisor_designations = ["Regional Operations Manager",
                         "Manager-Asset Operations",
                         "Manager-MB Asset Operations",
                         "Manager-Retail Asset Operations",
                         "Specialist-Asset Operations",
                         "Specialist- MB Asset Operations",
                         "Manager-Banking Operation"
                         ]; 

                    $ro_officer_designations = ['Associate',
                         'Officer-Asset Operations',
                         'Officer-MB Asset Operations',
                         'Officer-Micro Banking Asset Operations',
                         'Senior Officer-Micro Banking Asset Operations',
                         'Senior Officer-MB Asset Operations', 
                         'Senior Officer-Vehicle Loan Operations',
                         'Offcier-Banking Operations',
                         'Officer-Vehicle Loan Operations',
                         'Senior Officer-Banking Operations',
                         'Senior Officer-Asset Operations'
                         ];

                    $ro_readonly_designations = ["Area Head-Branch Banking",
                         "Area Manager-Branch Banking Operations",
                         "Cluster Head-Branch Banking",
                         "Cluster Head-Current Account",
                         "Regional Business Head-Branch Banking",
                         "Regional Head-Branch Banking",
                         "Regional Head-Corporate Salary",
                         "Regional Head-NR",
                         "Regional Manager-Branch Banking Operations",
                         "Regional Sales Head-Branch Banking",
                         "Senior Regional Head-Branch Banking",
                         "State Head-Branch Banking",
                         "State Sales Head-Branch Banking",
                         "Territory Sales Head-Branch Banking",
                         "Area Head-Gold Loan",
                         "Area Manager-Gold loans",
                         "Regional Business Manager-Gold Loan",
                         "Area Manager-Micro Banking",
                         "Distribution Manager-Micro Banking",
                         "Product Manager-Family Banking",
                         "Regional Business Manager-Micro Banking",
                         "Senior Area Manager-Micro Banking",
                         // "Manager-Banking Operation",
                         "Manager-Housing Loan Operations",
                         "Manager-MSME Operations",
                         "Manager-Operations Housing",
                         "Manager-Operations MSE",
                         "Manager-Payments",
                         "Manager-Payments and Settlements",
                         "Manager-Secured Loan Operations",  
                         "Manager-Vehicle Loan Operations",
                         "Specialist-Banking Operations",
                         "Specialist-NR Operations",             
                         ];     

                    $bo_checker_designations = ['Branch Manager',
                         'Branch Operation Manager',
                         'Branch Operations and Service Manager',
                         'Customer Care Representative-URC',
                         'Senior Branch Manager'
                         ];

                         $bo_maker_designations = ['Customer Care Representative',
                         'Cashier',
                         ];

                         $bo_readonly_designations = ['Branch Sales Manager',
                         'Assistant Customer Relationship Manager',
                         'Customer Relationship Manager'
                         ]; 


                    $ins_users = ['Officer-Insurance and TPP Operations',
                         'Specialist-Insurance and TPP Operations',
                         'Manager-Insurance and TPP Operations',
                         'Customer Care Representative',
                         'Cashier',
                         'Customer Relationship Manager',
                         'Assistant Customer Relationship Manager',
                         'Branch Operation Manager',
                         'Branch Manager',
                         'Head Of Operations',
                         'National Manager-Banking Operations/Regional Operations Manager'
                    ];

                    $ins_ho_user = ['Officer-Insurance and TPP Operations','Specialist-Insurance and TPP Operations'];

                    $ins_admin= ['Manager-Insurance and TPP Operations'];

                    $designation = trim($value->current_designation);

                    if(in_array($designation , $admin_designations)){
                         $role = 'admin';
                    }
                    
                    if(in_array($designation , $super_admin_designations)){
                         $role = 'super_admin';
                    }

                    if(in_array($designation , $ho_designations)){
                         $role = 'ho-user';
                    }

                    if(in_array($designation , $ro_supervisor_designations)){
                         $role = 'ro-supervisor';
                    }

                    if(in_array($designation , $ro_officer_designations)){
                         $role = 'ro-officer';
                    }

                    if(in_array($designation , $ro_readonly_designations)){
                         $role = 'ro-user';
                    }

                    if(in_array($designation , $bo_checker_designations)){
                         $role = 'bo-checker';
                    }

                    if(in_array($designation , $bo_maker_designations)){
                         $role = 'bo-maker';
                    }

                         if(in_array($designation , $bo_readonly_designations)){
                         $role = 'branch-user';
                    }

                    if(in_array($designation , $ins_ho_user)){
                         $role = 'ins-ho-user';
                    }

                    if(in_array($designation , $ins_admin)){
                         $role = 'ins-admin';
                    }

                    if(in_array($designation , $ins_users)){
                         $is_ins_user = '1';
                    }
               
                    
                    if($value->office_region == 'South') $region_id = '1';
                    if($value->office_region == 'North') $region_id = '2';
                    if($value->office_region == 'East') $region_id = '3';
                    if($value->office_region == 'West') $region_id = '4';
                    
                    
                    $user->first_name = $value->first_name;
                    $user->last_name = $value->last_name;
                    $user->middle_name = $value->middle_name;
                    $user->employee_id = $value->employee_id;
                    $user->email = $value->office_email;
                    $user->password = Hash::make('password');
                    $user->dor = date('Y-m-d',strtotime($value->doe));
                    $user->doj =  date('Y-m-d',strtotime($value->doj));
                    $user->mobile_number = $value->office_mobile;
                    $user->dob =  date('Y-m-d',strtotime($value->dob));
                    $user->gender =  $value->gender;
                    $user->status = $value->employee_status;
                    $user->branch_id = $branch_data[0];
                    $user->region = $value->office_region;  
                    $user->region_id = (int)$region_id;
                    // $user->ins_user = $is_ins_user;
                    $user->employee_type = $value->employee_type;  
                    $user->current_designation = $value->current_designation;  
                    $user->grade = $value->grade;  
                    $user->confirmation_status = $value->confirmation_status;  
                    $user->date_of_confirmation = $value->date_of_confirmation;  
                    $user->current_location_type = $value->current_location_type;  
                    $user->direct_manager_name = $value->direct_manager_name;  
                    $user->direct_manager_emp_id = $value->direct_manager_emp_id;  
                    $user->direct_manager_email = $value->direct_manager_email;  
                    $user->office_location = $value->office_location;  
                    $user->current_department = $value->current_department;  
                    $user->top_department = $value->top_department;  
                    $user->department_hierarchy_1_name = $value->department_hierarchy_1_name;  
                    $user->department_hierarchy_2_name = $value->department_hierarchy_2_name;  
                    $user->department_hierarchy_3_name = $value->department_hierarchy_3_name;  
                    $user->functional_head = $value->functional_head;  
                    $user->functional_head_emp_id = $value->functional_head_emp_id;  
                    $user->work_flow_role = $value->work_flow_role;  
                    $user->prac_designation = $value->prac_designation;  
                    $user->prac_role = $value->prac_role;  
                    $user->pac_designation = $value->pac_designation;  
                    $user->pac_role = $value->pac_role;
                    
                    if($user->isDirty()){
                         $user->save();
                         $user->syncRoles($role); 
                         \Log::info('User Details Updated : '.$user->employee_id);
                    }
                    else{
                         \Log::info('User Details Updated : '.$user->employee_id);
                    }
               }
          }
          \Log::info('User Syncing Done');
    }
}
