<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HRMData;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;



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
        
      //  $hrmData = HRMData::where('load_date',$currentDate)->get();
        $hrmData = HRMData::whereIn('id',['101','102','103','104','105','106','107','108','109','110','111','112'])->get();

        if($hrmData){
            foreach($hrmData as $key => $value){
                
                    $userDataRes = User::where('employee_id',$value->employee_id)->first();
                    $branchData = $value->office_loc_code;
                    $branch_data = explode('-',$branchData);
                    $region_id = '';

                    $role = 'bo-readonly';

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
                        "National Manager-Banking Operations",
                        "National Manager-Housing Loans ,Personal Loans and Vehicle Finance Operations",
                        "National Manager-MSME Operations and FIG; Operations",
                        "National Manager-Payments and Settlements" ];

                    $ro_supervisor_designations = ["Regional Operations Manager",
                        "Manager-Asset Operations",
                        "Manager-Retail Asset Operations",
                        "Specialist-Asset Operations"
                       ]; 

                    $ro_officer_designations = ['Associate',
                        'Officer-Asset Operations',
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
                        "Manager-Banking Operation",
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
                        'Senior Branch Manager',
                       ];

                     $bo_maker_designations = ['Customer Care Representative',
                        'Cashier',
                       ];

                     $bo_readonly_designations = ['Branch Sales Manager',
                        'Assistant Customer Relationship Manager',
                        'Customer Relationship Manager'
                       ];             

                    $designation = trim($value->current_designation);

                    // check for data existance
                    if($userDataRes){
                         $user = User::find($userDataRes->id);
                       
                    }else{
                        $user = new User;                    
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
                         $role = 'ro-readonly';
                    }

                    if(in_array($designation , $bo_checker_designations)){
                         $role = 'bo-checker';
                    }

                    if(in_array($designation , $bo_maker_designations)){
                         $role = 'bo-maker';
                    }

                     if(in_array($designation , $bo_readonly_designations)){
                         $role = 'bo-readonly';
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
                    $user->branch_id = is_int($branch_data[0]) ? $branch_data[0] : '0000';
                    $user->region = $value->office_region;  
                    $user->region_id = (int)$region_id;
                    $user->save();



                    $user->syncRoles($role); 

                    
            }

            echo 'Success';
        }
    }
}
