<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class CheckInsuranceUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,...$code)
    {
    	//print_r(Auth::user()->ins_user);die();
        if (!Auth::check()) {
            abort(403, 'Not authenticated.');
        }

        $user = Auth::user();

        if ($user->ins_user != 1) {
            abort(403, 'Insurance access is required.');
        }

        $rolecode = '3';
        $designation = Auth::user()->hrmData->current_designation;

       // print_r($designation);die();

        $bo_checker_designations = ['Branch Manager',
	        'Branch Operation Manager',
	        'Branch Operations and Service Manager',
	        'Customer Care Representative-URC',
	        'Senior Branch Manager',
	        'Customer Care Representative',
	        'Cashier',
	        'Area Head-Gold Loan'
	       ];

	    
	     $ins_admin= ['Manager-Insurance and TPP Operations'];  

	     $ins_ho_user = ['Officer-Insurance and TPP Operations','Specialist-Insurance and TPP Operations'];

	     if(in_array($designation , $bo_checker_designations)){
                 $rolecode = '3';
            }

	      if(in_array($designation , $ins_ho_user)){
                 $rolecode = '2';
            }

            if(in_array($designation , $ins_admin)){
                 $rolecode = '1';
            }  
            

            //print_r($rolecode);die();

        if (empty($rolecode) || in_array($rolecode, $code)) {
            return $next($request);
        }

        abort(403, 'Access denied for your designation.');
    }
}
