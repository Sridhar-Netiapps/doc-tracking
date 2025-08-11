<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HRMData;
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
        
        $hrmData = HRMData::where('load_date',$currentDate)->get();

        if($hrmData){
            foreach($hrmData as $key => $value){
                
                    $userDataRes = User::where('employee_id',$value->employee_id)->first();
                    $branchData = $value->office_loc_code;
                    $branch_data = explode('-',$branchData);
                    $region_id = '';
                    if($userDataRes){
                         $user = User::find($userDataRes->id);
                       
                    }else{
                        $user = new User;                    
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
                    $user->password = Hash::make('admin');
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

                    
            }

            echo 'Success';
        }
    }
}
