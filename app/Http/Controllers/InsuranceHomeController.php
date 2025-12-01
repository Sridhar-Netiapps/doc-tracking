<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Str;

use App\Models\InsuranceProduct;
use App\Models\InsuranceCauseOfDeath;
use App\Models\InsuranceClaimStatus;
use App\Models\InsurancePlaceofDeath;
use App\Models\InsurancePartner;
use App\Models\InsuranceRelationship;
use App\Models\InsuranceRequestLetterStatus;
use App\Models\InsuranceClaimDetail;
use App\Models\InsuranceNomineeDetail;
use App\Models\InsuranceChecklist;
use App\Models\InsuranceDocument;
use App\Models\Region;
use App\Models\AdditionalField;
use App\Models\AdditionalFieldSetting;


use App\Imports\ImportClaimDetails;
use App\Exports\ExportInsuranceLeads;
use App\Exports\ExportErrorRows;
use App\Exports\ExportAuditLogs;

use App\Models\AuditLog;
use App\AuditLogTrait;
use App\Models\Branch;

use File;
use Excel;
use Auth;
use ZipArchive;
use Smalot\PdfParser\Parser;
use App\Mail\IntimationResponseMail;
use Mail;


class InsuranceHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use AuditLogTrait; 

    public function index(Request $request)
    {
        
        $partners = InsurancePartner::get();
        $partnerNameArray=array();
        $partnerCountArray=array();
        $region = ['North','South','East','West'];
        $regionNameArray=array();
        $regionCountArray=array();
        $claimedArray = array();
        $noneligibleArray = array();
        $rejectedArray = array();
        $inprogressArray = array();
        $claimedAmount = array();
        $claimchart=array();
        $causenames=array();
        $deathcounts=array();
        $deathagegroup=array();
        

        
        $currentYear = date('Y');
        $currentMonth = date('m');
        $slectedyear=$request->fy;


        if ($currentMonth >= 4) {
            $startYear = $currentYear;
            $financeyear = $startYear .'-'.$startYear +1 ;
        } else {
            $startYear = $currentYear - 1;
            $financeyear = $startYear-1 .'-'.$startYear;
        }

        $financialYears = [];
        for ($i = 0; $i < 10; $i++) {
            $endYear = $startYear + 1;
            $financialYears[] = $startYear . '-' . $endYear; // Format as 'YYYY-YY'
            $startYear--;
        }

        if($request->fy == ''){
           $fy =  explode('-',$financeyear);
        }
        else{
           $fy = explode('-',$slectedyear);
        }
        
         $start = $fy[0].'-04-01';
         $end = $fy[1].'-03-31';

        if(Auth::user()->branch_id == '1100'){
          $total = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->count();
          $claimed = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->count();
          $pending_at_branch = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->whereIn('cliam_status',['Pending From Branch','Pending from branch-Require additional documents'])->count();
          $doc_at_ho = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Document Sent to HO to Process')->count();
          $inprogress = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->whereIn('cliam_status',['Pending from Insurance Company'])->count();
          
          foreach ($partners as $key => $value) {
              $partnerArray[]=$value->partner;
              $partnerCountArray[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('partner',$value->partner)->count();
          }

          $partnerChart = ['names' => $partnerArray , 'counts' => $partnerCountArray];

          
          foreach ($region as $val) {
              $claimedArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('region',$val)->where('cliam_status','Completed')->count();
              $noneligibleArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('region',$val)->whereIn('cliam_status',['Not Eligible','Not Eligible [Having outstanding]'])->count();
              $rejectedArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('region',$val)->where('cliam_status','Rejected')->count();
              $inprogressArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('region',$val)->whereNotIn('cliam_status',['Completed','Not Eligible','Not Eligible [Having outstanding]','Rejected',])->count();
          } 

          $regionChart = [$claimedArray ,$noneligibleArray , $rejectedArray , $inprogressArray]; 

          $monthArray=$this->getFinancialYearMonths($fy[0]);
          
          foreach ($monthArray as $vals) {
              $claimedAmount[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('claim_amount');
              $settledAmount[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('payable_to_nominee');
              $claimcount[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->count();


          }
          
          $claimchart=[$claimcount , $settledAmount , $claimedAmount];

          $causeofdeath =  InsuranceCauseOfDeath::all();

          foreach ($causeofdeath as $key => $value) {
             $deathcounts[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->where('cause_of_death',$value->cause)->count();
             $causenames[]=$value->cause;
          }

          $deathcausehart = [ $causenames , $deathcounts];

          $count10 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[0,10])->count();
          $count20 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[11,20])->count();
          $count30 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[21,30])->count();
          $count40 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[31,40])->count();
          $count50 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[41,50])->count();
          $count60 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[51,60])->count();
          $count70 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[61,70])->count();
          $count80 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[71,80])->count();
          $count90 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[81,90])->count();
          $count100 = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[91,100])->count();

          $deathagegroup = [$count10,$count20,$count30,$count40,$count50,$count60,$count70,$count80,$count90,$count100];

        } 
        else{
        
          $total = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->count();
          $claimed = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('cliam_status','Completed')->count();
          $pending_at_branch = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->whereIn('cliam_status',['Pending From Branch','Pending from branch-Require additional documents'])->count();
          $doc_at_ho = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('cliam_status','Document Sent to HO to Process')->count();
          $inprogress = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->whereIn('cliam_status',['Pending from Insurance Company'])->count();

           foreach ($partners as $key => $value) {
              $partnerArray[]=$value->partner;
              $partnerCountArray[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('partner',$value->partner)->count();
          }

          $partnerChart = ['names' => $partnerArray , 'counts' => $partnerCountArray];

          
          foreach ($region as $val) {
              $claimedArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->where('cliam_status','Completed')->count();
              $noneligibleArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->whereIn('cliam_status',['Not Eligible','Not Eligible [Having outstanding]'])->count();
              $rejectedArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->where('cliam_status','Rejected')->count();
              $inprogressArray[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->whereNotIn('cliam_status',['Completed','Not Eligible','Not Eligible [Having outstanding]','Rejected',])->count();
           } 

           $regionChart = [$claimedArray ,$noneligibleArray , $rejectedArray , $inprogressArray];  

           $monthArray=$this->getFinancialYearMonths($fy[0]);
          
          foreach ($monthArray as $vals) {
              $claimedAmount[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('claim_amount');
              $settledAmount[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('payable_to_nominee');
              $claimcount[]=InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->count();
          }
          
          $claimchart=[$claimcount , $settledAmount , $claimedAmount];
          
          $causeofdeath =  InsuranceCauseOfDeath::all();
          foreach ($causeofdeath as $key => $value) {
             $deathcounts[] = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])->where('branch',Auth::user()->branch_id)->where('cliam_status','Completed')->where('cause_of_death',$value->cause)->count();
             $causenames[]=$value->cause;
          }

          $deathcausehart = [ $causenames , $deathcounts];

          $count10 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[0,10])->count();
          $count20 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[11,20])->count();
          $count30 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[21,30])->count();
          $count40 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[31,40])->count();
          $count50 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[41,50])->count();
          $count60 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[51,60])->count();
          $count70 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[61,70])->count();
          $count80 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[71,80])->count();
          $count90 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[81,90])->count();
          $count100 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('intimation_date',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[91,100])->count();

          $deathagegroup = [$count10,$count20,$count30,$count40,$count50,$count60,$count70,$count80,$count90,$count100];

         }

        //print_r(json_encode($deathcausehart));die();
        return view('insurance.dashboard',compact('total','claimed','pending_at_branch','doc_at_ho','inprogress','partnerChart','regionChart','claimchart','claimedAmount','monthArray','deathcausehart','financialYears','slectedyear','deathagegroup'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function list(Request $request)
    {
        $search='';

        if(isset($request->search)){
           $search=$request->search;  
        }

        if(Auth::user()->branch_id == '1100'){
          $data = InsuranceClaimDetail::when($search,function($query)use($search){
               $query->where('actual_id','LIKE',$search.'%');
               $query->orWhere('load_acc_id','LIKE',$search.'%');
               $query->orWhere('utrn','LIKE','%'.$search.'%');
               $query->orWhere('partner','LIKE','%'.$search.'%');
               $query->orWhere('product','LIKE','%'.$search.'%');
               $query->orWhere('deceased_name','LIKE',$search.'%');
               $query->orWhere('deceased','LIKE',$search.'%');
               $query->orWhere('cause_of_death','LIKE','%'.$search.'%');
               $query->orWhere('cliam_status','LIKE','%'.$search.'%');
               $query->orWhere('claim_amount','LIKE','%'.$search.'%');
          })
          ->orderByRaw("CASE WHEN cliam_status != 'completed' THEN 0 ELSE 1 END")
          ->orderBy('id','DESC')->paginate(25);
        
        }else{
           $data = InsuranceClaimDetail::where('branch','LIKE',Auth::user()->branch_id.'%')
              ->where(function($qr)use($search){
               $qr->when($search,function($query)use($search){
                   $query->where('cust_id','LIKE',$search.'%');
                   $query->orWhere('load_acc_id','LIKE',$search.'%');
                   $query->orWhere('utrn','LIKE','%'.$search.'%');
                   $query->orWhere('partner','LIKE','%'.$search.'%');
                   $query->orWhere('product','LIKE','%'.$search.'%');
                   $query->orWhere('deceased_name','LIKE',$search.'%');
                   $query->orWhere('deceased','LIKE',$search.'%');
                   $query->orWhere('cause_of_death','LIKE','%'.$search.'%');
                   $query->orWhere('cliam_status','LIKE','%'.$search.'%');
                   $query->orWhere('claim_amount','LIKE','%'.$search.'%');
                  });
              })
              ->orderByRaw("CASE WHEN cliam_status != 'completed' THEN 0 ELSE 1 END")
              ->orderBy('id','DESC')->paginate(25); 
        }

        $landingTab = (Auth::user()->branch_id == '1100') ?'ho':'bo';
        
        return view('insurance.index',compact('data','search','landingTab'));
    }

    public function claim_forms(){
        $path = public_path('insurance_images/hdfc.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = PDF::loadView('insurance.templates.checklist', ['base64' => $base64])->setPaper('A4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
        //return $pdf->stream('document.pdf');
        return $pdf->download('checklist.pdf');
       
       // return view('templates.bajaj');
    }
    public function create()
    {
        $partners = InsurancePartner::get();
        $products = InsuranceProduct::get();
        $placeofdeath = InsurancePlaceofDeath::get();
        $relationship = InsuranceRelationship::get();
        $deathcause = InsuranceCauseOfDeath::get();
        $claimstatus = InsuranceClaimStatus::get();
        $rlStat=InsuranceRequestLetterStatus::get();
        $additionalfields = AdditionalFieldSetting::get();
        $branch = Branch::get();
        $regions = Region::get();

        $procesedby=['NA','Vindhya','HO'];  
        $deceased=['Applicant','Co-Applicant','Spouse','Customer'];

        return view('insurance/create',compact('partners','products','placeofdeath','relationship','deathcause','claimstatus','procesedby','rlStat','deceased','branch','additionalfields', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // print_r($request->input()); die();

      $inputdata = $request->all();
      $errors = [];
 

      $request->validate([
          'region' => 'required',
          'branch' => 'required',
          'partner' => 'required',
          'product' => 'required',
          'policy_covered_date' => 'required',
          'actual_id' => 'required',
          'deceased_name' => 'required',
          'date_of_death' => ['required','before_or_equal:today'],
          'load_acc_id' => 'required|max:25',
          'claim_amount' => 'required|max:20',
          'loan_amount' => 'nullable|max:20',
          'loan_outstanding' => 'nullable|max:20',
          'recovered_amount' => 'nullable|max:20',
          'payable_to_nominee' => 'nullable|max:20',
          'policy_expiry_date' => ['nullable','date', 'after_or_equal:policy_covered_date'],
          'intimation_date' => ['required','date', 'after_or_equal:policy_covered_date','after_or_equal:date_of_death'],
          'doc_rec_date' => ['nullable','date', 'after_or_equal:intimation_date'],
          'resubmission_to_partner_date' => ['nullable','date', 'after_or_equal:submit_to_partner_date'],
             
      ]);

     foreach ($inputdata as $key => $value) {
          if (is_string($value)) {
              if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $value)) {
                  $errors[$key] = 'Script tags are not allowed.';
              } elseif (!preg_match('/^[a-zA-Z0-9 ,._\/&-]+$/', $value)) {
                  $errors[$key] = 'Only letters, numbers, spaces, and , . - are allowed.';
              }
          }
      }

      if (!empty($errors)) {
          return redirect()->back()->withErrors($errors)->withInput();
      }

        $request->merge([
          'claim_amount' => preg_replace('/[^0-9.]/', '', $request->claim_amount), 
          'loan_amount' => preg_replace('/[^0-9.]/', '', $request->loan_amount),
          'loan_outstanding' => preg_replace('/[^0-9.]/', '', $request->loan_outstanding),
          'payable_to_nominee' => preg_replace('/[^0-9.]/', '', $request->payable_to_nominee),
          'recovered_amount' => preg_replace('/[^0-9.]/', '', $request->recovered_amount),
          
        ]);

        $utrn = rand('000000','999999');
        $claimdata = new InsuranceClaimDetail;
        $claimdata->utrn = "INS_CLM".$utrn;
        $claimdata->branch = $request->branch;
        $claimdata->partner = $request->partner;
        $claimdata->product = $request->product;
        $claimdata->region = $request->region;
        $claimdata->policy_number = $request->policy_number;
        $claimdata->mp_no = $request->mp_no;
        $claimdata->policy_covered_date = $request->policy_covered_date;
        $claimdata->policy_expiry_date = $request->policy_expiry_date;
        $claimdata->cust_id = $request->cust_id;
        $claimdata->actual_id = $request->actual_id;
        $claimdata->deceased_name = $request->deceased_name;
        $claimdata->dob = $request->dob;
        $claimdata->date_of_death = $request->date_of_death;
        $claimdata->gender = $request->gender;    
        $claimdata->age = $request->age; 
        $claimdata->deceased = $request->deceased;
        $claimdata->intimation_date = $request->intimation_date;
        $claimdata->place_of_death = $request->place_of_death;
        $claimdata->cause_of_death = $request->cause_of_death;
        $claimdata->load_acc_id = $request->load_acc_id;
        $claimdata->loan_tenure = $request->loan_tenure;
        $claimdata->claim_amount = $request->claim_amount;
        $claimdata->nominee_name = $request->nominee_name;
        $claimdata->relationship = $request->relationship;

        $claimdata->doc_rec_date = $request->doc_rec_date;
        $claimdata->processed_by = $request->processed_by;
        $claimdata->ho_remark = $request->ho_remark;
        $claimdata->submit_to_partner_date = $request->submit_to_partner_date;
        $claimdata->ho_remark2 = $request->ho_remark2;
        $claimdata->re_submit_to_partner_date = $request->resubmission_to_partner_date;
        $claimdata->cliam_status = $request->cliam_status;
        $claimdata->cas_status = $request->cas_status;
        $claimdata->rl_status = $request->rl_status;
        $claimdata->notification_number = $request->notification_number;

        $claimdata->loan_amount = $request->loan_amount;
        $claimdata->loan_outstanding = $request->loan_outstanding;
        $claimdata->payable_to_nominee = $request->payable_to_nominee;
        $claimdata->settlement_date = $request->settlement_date;
        $claimdata->neft_rejection_date = $request->neft_rejection_date;
        $claimdata->neft_rejection_reason = $request->neft_rejection_reason;
        $claimdata->final_settlement_date = $request->final_settlement_date;
        $claimdata->utrn_mph = $request->utrn_mph;
        $claimdata->utrn_nominee = $request->utrn_nominee;

        $claimdata->recovery_status = $request->recovery_status;
        $claimdata->recoveries = $request->recoveries;
        $claimdata->bounced_chq_no = $request->bounced_chq_no;
        $claimdata->chq_deposit_date = $request->chq_deposit_date;
        $claimdata->bounced_chq_date = $request->bounced_chq_date;
        $claimdata->bounced_chq_reason = $request->bounced_chq_reason;
        $claimdata->recovered_amount = $request->recovered_amount;

        $claimdata->write_off_rec = $request->write_off_rec;
        $claimdata->write_off_status = $request->write_off_status;
        $claimdata->handed_to_bh = $request->handed_to_bh;
        $claimdata->handed_to_credit = $request->handed_to_credit;
        $claimdata->ho_employee_id = Auth::user()->employee_id;
        $claimdata->latest_editor = Auth::user()->employee_id;

        $claimdata->save();

        if($claimdata->id !='' || $claimdata->id != 0){
            InsuranceNomineeDetail::create(
              [
                'insurance_claim_details_id' => $claimdata->id,
                'spdc_rec_date' => $request->spdc_rec_date,
                'ack_rec_date' => $request->ack_rec_date ,
                'pkt_no' => $request->pkt_no 
              ]);

            $dynamicData = collect($request->all())
              ->filter(function ($value, $key) {
                  return Str::startsWith($key, 'af_');
              })
              ->mapWithKeys(function ($value, $key) {
                  // Extract numeric ID from the name af_1 => 1
                  $fieldId = (int) str_replace('af_', '', $key);
                  return [$fieldId => $value];
              })
              ->toArray();

           
            foreach ($dynamicData as $fieldId => $fieldValue) {
               $setting = AdditionalFieldSetting::where('id',$fieldId)->first();
                AdditionalField::create(
                    ['insurance_claim_details_id' => $claimdata->id,'additional_field_settings_id'=>$fieldId, 'param_name' => $setting->field_name,'param_value' => $fieldValue,'creator'=>Auth::user()->employee_id]
                   
                );
            }

           
           // InsuranceChecklist::create(['insurance_claim_details_id' => $claimdata->id]);

            $mailData=['message' => 'New Lead created in Insurance Module.Please refer Lead ID - '.$claimdata->utrn.' for detailed information'] ;
            $reciepients=array();
            $reciepients=['druva@netiapps.com'];
            $csvContent='';
            $fileName = '';
            $result = IntimationResponseMail::sendThrottled($reciepients , $mailData ,$csvContent, $fileName);

             $module = 'Insurance'; 
             $operation = 'create';
             $note = 'New Lead created - '."INS_CLM".$utrn;
             $link = url('/').'/insurance/view_claim_details/'.encrypt($claimdata->id);

            $this->auditlogs($module , $operation ,$note , $link);

            return redirect()->back()->with('success','Saved Succesfully');
        }
        else{
            return redirect()->back()->with('failure','Error while Saving data');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = InsuranceClaimDetail::where('id',decrypt($id))->first();
        $partners = InsurancePartner::get();
        $products = InsuranceProduct::get();
        $placeofdeath = InsurancePlaceofDeath::get();
        $relationship = InsuranceRelationship::get();
        $deathcause = InsuranceCauseOfDeath::get();
        $claimstatus = InsuranceClaimStatus::get();
        $rlStat=InsuranceRequestLetterStatus::get();
        $procesedby=['NA','Vindhya','HO'];
        $branch = Branch::get();
        $regions = Region::get();

        $landingTab = (Auth::user()->branch_id == '1100' ? 'ho' :'bo'); 
        $deceased=['Applicant','Co-Applicant','Spouse','Customer'];
        $checklistdata = InsuranceChecklist::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
        $nomineedata = InsuranceNomineeDetail::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
        $documentdata = InsuranceDocument::where('insurance_claim_details_id',decrypt($id))->where('status','1')->orderBy('id','ASC')->get();
       
        $productDetails = InsuranceProduct::where('product',$data->product)->first();
        $formArray=array();
        $claimformtype =$productDetails->type;
        if($claimformtype == 'NMB'){
           $folder =  $productDetails->folder_name;
           $files = File::files(('template/'.$folder));
          // print_r($files);die();
           foreach ($files as $file) {
              //$relativePath = str_replace(public_path(), '', $file->getRealPath());
              $formArray[] = 'template/'.$folder.'/'.$file->getFilename();
          }


        }

        $additionalLeadfields = AdditionalField::where('insurance_claim_details_id',decrypt($id))->with('settingData')->get();
         //print_r(json_encode($additionalLeadfields));die();
        
        return view('insurance.view',compact('data','partners','products','placeofdeath','relationship','deathcause','claimstatus','procesedby','rlStat','deceased','checklistdata','nomineedata','formArray','branch','documentdata','landingTab','additionalLeadfields','regions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($spec ,  $id)
    {
       // print_r($spec);die();
        $data = InsuranceClaimDetail::where('id',decrypt($id))->first();
        $partners = InsurancePartner::get();
        $products = InsuranceProduct::get();
        $placeofdeath = InsurancePlaceofDeath::get();
        $relationship = InsuranceRelationship::get();
        $deathcause = InsuranceCauseOfDeath::get();
        $claimstatus = InsuranceClaimStatus::get();
        $rlStat=InsuranceRequestLetterStatus::get();
        $procesedby=['NA','Vindhya','HO'];
         $branch = Branch::get();
          $regions = Region::get();
        
        $deceased=['Applicant','Co-Applicant','Spouse','Customer'];
        $checklistdata = InsuranceChecklist::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
        $nomineedata = InsuranceNomineeDetail::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
        $documentdata = InsuranceDocument::where('insurance_claim_details_id',decrypt($id))->where('status','1')->orderBy('id','ASC')->get();
       // print_r($checklistdata);die();
        $productDetails = InsuranceProduct::where('product',$data->product)->first();
        $formArray=array();
        $claimformtype =$productDetails->type;
        if($claimformtype == 'NMB'){
           $folder =  $productDetails->folder_name;
           $files = File::files(('template/'.$folder));
          // print_r($files);die();
           foreach ($files as $file) {
              //$relativePath = str_replace(public_path(), '', $file->getRealPath());
              $formArray[] = 'template/'.$folder.'/'.$file->getFilename();
          }


        }

       $allSettings = AdditionalFieldSetting::all();

      // get existing child values for this claim
      $existingFields = AdditionalField::where('insurance_claim_details_id', decrypt($id))
          ->get()
          ->keyBy('additional_field_settings_id'); // key by parent id

        session()->forget('message');
        session()->forget('failures');
        
      // print_r($formArray);die();
        return view('insurance.edit',compact('data','partners','products','placeofdeath','relationship','deathcause','claimstatus','procesedby','rlStat','deceased','checklistdata','nomineedata','formArray','branch','documentdata','spec','allSettings','existingFields','regions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     // print_r($request->input());die();

        $request->validate([
          'region' => 'required',
          'branch' => 'required',
          'partner' => 'required',
          'product' => 'required',
          'policy_covered_date' => 'required',
          'actual_id' => 'required',
          'deceased_name' => 'required',
          'date_of_death' => ['required','before_or_equal:today'],
          'load_acc_id' => 'required|max:25',
          'claim_amount' => 'required|max:20',
          'loan_amount' => 'nullable|max:20',
          'loan_outstanding' => 'nullable|max:20',
          'recovered_amount' => 'nullable|max:20',
          'payable_to_nominee' => 'nullable|max:20',
          'policy_expiry_date' => ['nullable','date', 'after_or_equal:policy_covered_date'],
          'doc_rec_date' => ['nullable','date', 'after_or_equal:intimation_date'],
          'resubmission_to_partner_date' => ['nullable','date', 'after_or_equal:submit_to_partner_date'],
          'intimation_date' => ['required','date', 'after_or_equal:policy_covered_date','after_or_equal:date_of_death'],
         
         
      ]);

        $request->merge([
          'claim_amount' => preg_replace('/[^0-9.]/', '', $request->claim_amount), 
          'loan_amount' => preg_replace('/[^0-9.]/', '', $request->loan_amount),
          'loan_outstanding' => preg_replace('/[^0-9.]/', '', $request->loan_outstanding),
          'payable_to_nominee' => preg_replace('/[^0-9.]/', '', $request->payable_to_nominee),
          'recovered_amount' => preg_replace('/[^0-9.]/', '', $request->recovered_amount),
          
        ]);

        $inputdata = $request->all();
        $errors = [];

        //print_r($request->claim_amount);die();

        foreach ($inputdata as $key => $value) {

          if (is_null($value) || $value === '') {
              continue;
          }

          if (is_string($value)) {
              if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $value)) {
                  $errors[$key] = 'Script tags are not allowed.';
              } elseif (!preg_match('/^[a-zA-Z0-9 ,._\/&-]+$/', $value)) {
                  $errors[$key] = 'Only letters, numbers, spaces, and , . - are allowed.';
              }
          }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $claimdata = InsuranceClaimDetail::find(decrypt($id));
        $claimdata->branch = $request->branch;
        $claimdata->partner = $request->partner;
        $claimdata->product = $request->product;
        $claimdata->region = $request->region;
        $claimdata->mp_no = $request->mp_no;
        $claimdata->policy_number = $request->policy_number;
        $claimdata->policy_covered_date = $request->policy_covered_date;
        $claimdata->policy_expiry_date = $request->policy_expiry_date; 
        $claimdata->cust_id = $request->cust_id;
        $claimdata->actual_id = $request->actual_id;
        $claimdata->deceased_name = $request->deceased_name;
        $claimdata->dob = $request->dob;
        $claimdata->date_of_death = $request->date_of_death;
        $claimdata->gender = $request->gender;
        $claimdata->age = $request->age;
        $claimdata->deceased = $request->deceased;
        $claimdata->intimation_date = $request->intimation_date;
        $claimdata->place_of_death = $request->place_of_death;
        $claimdata->cause_of_death = $request->cause_of_death;
        $claimdata->load_acc_id = $request->load_acc_id;
        $claimdata->loan_tenure = $request->loan_tenure;
        $claimdata->claim_amount = $request->claim_amount;
        $claimdata->nominee_name = $request->nominee_name;
        $claimdata->relationship = $request->relationship;

        $claimdata->submit_to_partner_date = $request->submit_to_partner_date;
        $claimdata->processed_by = $request->processed_by;
        $claimdata->doc_rec_date = $request->doc_rec_date;
        $claimdata->re_submit_to_partner_date = $request->resubmission_to_partner_date;
        $claimdata->ho_remark = $request->ho_remark;
        $claimdata->ho_remark2 = $request->ho_remark2;
        $claimdata->cliam_status = $request->cliam_status;
        $claimdata->cas_status = $request->cas_status;
        $claimdata->rl_status = $request->rl_status;
        $claimdata->notification_number = $request->notification_number;
        
        $claimdata->loan_amount = $request->loan_amount;
        $claimdata->loan_outstanding = $request->loan_outstanding;
        $claimdata->payable_to_nominee = $request->payable_to_nominee;
        $claimdata->settlement_date = $request->settlement_date;
        $claimdata->neft_rejection_date = $request->neft_rejection_date;
        $claimdata->neft_rejection_reason = $request->neft_rejection_reason;
        $claimdata->final_settlement_date = $request->final_settlement_date;
        $claimdata->utrn_mph = $request->utrn_mph;
        $claimdata->utrn_nominee = $request->utrn_nominee;
      
        
        $claimdata->recovery_status = $request->recovery_status;
        $claimdata->recoveries = $request->recoveries;
        $claimdata->bounced_chq_no = $request->bounced_chq_no;
        $claimdata->bounced_chq_date = $request->bounced_chq_date;
        $claimdata->bounced_chq_reason = $request->bounced_chq_reason;

        $claimdata->chq_deposit_date = $request->chq_deposit_date;
        $claimdata->recovered_amount = $request->recovered_amount;
        
        $claimdata->write_off_rec = $request->write_off_rec;
        $claimdata->write_off_status = $request->write_off_status;
        $claimdata->handed_to_bh = $request->handed_to_bh;
        $claimdata->handed_to_credit = $request->handed_to_credit;
       // $claimdata->ho_employee_id = Auth::user()->employee_id;
        $claimdata->latest_editor = Auth::user()->employee_id;

        $claimdata->save();

        if($claimdata->id !='' || $claimdata->id != 0){
            //InsuranceNomineeDetail::create(['insurance_claim_details_id' => decrypt($id) ]);
           // InsuranceChecklist::create(['insurance_claim_details_id' => decrypt($id) ]);

      $insurancenomineedata=InsuranceNomineeDetail::where('insurance_claim_details_id',decrypt($id))->first();
       if($insurancenomineedata) {
           $nomineedetail = InsuranceNomineeDetail::find($insurancenomineedata->id);
       }else{
         $nomineedetail = new InsuranceNomineeDetail;
         $nomineedetail->insurance_claim_details_id = decrypt($id);
       }
 
       $nomineedetail->spdc_rec_date = $request->spdc_rec_date;
       $nomineedetail->ack_rec_date = $request->ack_rec_date;
       $nomineedetail->pkt_no = $request->pkt_no;

       $nomineedetail->save();

       $dynamicData = collect($request->all())
            ->filter(function ($value, $key) {
                return Str::startsWith($key, 'af_');
            })
            ->mapWithKeys(function ($value, $key) {
                // Extract numeric ID from the name af_1 => 1
                $fieldId = (int) str_replace('af_', '', $key);
                return [$fieldId => $value];
            })
            ->toArray();

        foreach ($dynamicData as $settingId => $fieldValue) {
            $setting = AdditionalFieldSetting::find($settingId);

            if ($setting) {
                AdditionalField::updateOrCreate(
                    [
                        'insurance_claim_details_id'    => $claimdata->id,
                        'additional_field_settings_id'  => $setting->id,
                    ],
                    [
                        'param_name'  => $setting->field_name,
                        'param_value' => $fieldValue,
                        'creator'     => Auth::user()->employee_id,
                    ]
                );
            }
        }
 
        $mailData=['message' => 'The Lead details are updated to the Insurance Module.Please refer Lead ID - '.$claimdata->utrn.' to view detailed information'];
        $reciepients=array();
        $reciepients=['druva@netiapps.com'];
        $csvContent='';
        $fileName = '';
       // print_r($csvContent);die();

        $result = IntimationResponseMail::sendThrottled($reciepients , $mailData ,$csvContent, $fileName);

             $module = 'Insurance'; 
             $operation = 'Update';
             $note = 'Lead details updated - '.$claimdata->utrn;
             $link = url('/').'/insurance/view_claim_details/'.encrypt($claimdata->id);

            $this->auditlogs($module , $operation ,$note , $link);

            return redirect()->back()->with('success','Updated Succesfully');
        }
        else{
            return redirect()->back()->with('failure','Error while Updating data');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function download_claim_form(string $id)
    {
        $claimdata = InsuranceClaimDetail::with('nominee')->where('id',decrypt($id))->first();

        $partner_type = InsuranceProduct::where('product',$claimdata->product)->first();

        $claimform =$partner_type->description;

        if($partner_type->type == 'MB'){
        
          if($claimdata->partner == 'Bajaj'){
              $path = public_path('insurance_images/bajaj_logo.png');
              $partner = 'BAJAJ';
              $formname='bajaj';
          }

          elseif($claimdata->partner == 'ABSLI'){
              $path = public_path('insurance_images/birlalogo.png');
              $partner = 'BIRLA';
              $formname='birlagroup';
          }

          elseif($claimdata->partner == 'HDFC'){
              $path = public_path('insurance_images/hdfc.jpg');
              $partner = 'HDFC';
              $formname='hdfc';
          }

          elseif($claimdata->partner == 'Max Life'){
              $path = public_path('insurance_images/maxlife.png');
              $partner = 'MAXLIFE';
              $formname='maxlife';
          }
          else{
            return redirect()->back()->with('error', 'No claim form available');
          }


         // print_r(json_encode($claimdata));die();
          
          $type = pathinfo($path, PATHINFO_EXTENSION);
          $data = file_get_contents($path);
          $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

          $pdf = PDF::loadView('insurance.templates.'.$formname, ['base64' => $base64,'data'=> $claimdata])->setPaper('A4', 'portrait')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                  ]);

           $module = 'Insurance'; 
           $operation = 'Download Claim Form';
           $note = 'Downloaded/Viewed Claim Form - '.$claimdata->utrn;
           $link = url('/').'/insurance/view_claim_details/'.encrypt($claimdata->id);

          $this->auditlogs($module , $operation ,$note , $link);        
          return $pdf->stream($partner.'_MB_'.$claimdata->utrn.'.pdf');
         // return $pdf->download($partner.'_'.$claimdata->cust_id.'.pdf');

         }
         else if($partner_type->type == 'NMB'){
            

             $this->downloadFolderSmart($partner_type->folder_name);      
          }
          else{
            
          }

         


    }


    public function downloadFolderSmart($folderName)
    {
       
      $folderPath = public_path('template/' . $folderName);

      if (!File::exists($folderPath)) {
          return response()->json(['error' => 'Folder not found'], 404);
      }

      $files = File::allFiles($folderPath);

      if (count($files) === 0) {
          return response()->json(['error' => 'No files found'], 404);
      }

      // 🔽 If only one file, download it directly
      if (count($files) === 1) {
          $file = $files[0];
          $realPath = $file->getRealPath();
          $fileName = str_replace(' ', '_', $file->getFilename()); // sanitize name
          
           if (!file_exists($realPath)) {
              return response()->json(['error' => 'File not found on disk'], 404);
          }

          // ✅ Clean output buffer
          if (ob_get_level()) {
              ob_end_clean();
          }

          // ✅ Try both options:
          return response()->download($realPath, $fileName); // recommended
         
      }


      // 🔽 If multiple files, zip and download
      $zipFileName = $folderName . '.zip';
      $zipPath = storage_path('app/' . $zipFileName);

      if (File::exists($zipPath)) {
          File::delete($zipPath);
      }

      $zip = new \ZipArchive();
      if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
          foreach ($files as $file) {
              $relativePath = str_replace($folderPath . '/', '', $file->getRealPath());
              $zip->addFile($file->getRealPath(), $relativePath);
          }
          $zip->close();
      } else {
          return response()->json(['error' => 'Could not create zip file'], 500);
      }

      return response()->download($zipPath)->deleteFileAfterSend(true);
    }



    public function import_claim_data(Request $request){

       $import = new ImportClaimDetails ;
       $file = $request->file('file');
       $errors=array();

     
       if ($request->hasFile('file')) {

            foreach ($request->file('file') as $file) {
           
                $result = $this->validateFileWhileSaving($file);
                if (strpos($result, 'Malicious content detected') !== false || 
                    strpos($result, 'Invalid file type') !== false || 
                    strpos($result, 'File size exceeds') !== false) {
                    $errors[] = $result;
                    continue; // skip saving this file
                }
              
            }

            if(sizeof($errors)>0){
               return response()->json([
                    'status' => 'false',
                    'message' => implode(',', $errors)
                ]);
            }
        }

      Excel::import($import, $request->file('file'));

       if (file_exists(public_path().'/template/Imports/')) {  
        } else {
          File::makeDirectory(public_path().'/template/Imports/', $mode = 0775, true, true);
        }
       
        $filepath = '/template/Imports';

    
        if ($request->hasFile('file')) {
         
             $doc_file = $request->file('file') ;
             $temp = explode(".", $doc_file->getClientOriginalName());
             $newName = date('YmdHis') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

             $destinationPath = public_path().$filepath;

             /* if ($doc_file->move($destinationPath,$newName)) {
                    $mailData=[
                      'message' => 'The Lead details are imported to the Insurance Module .Please find the attachemnt of the same .'];
                    $reciepients=array();
                    $reciepients=['druva@netiapps.com'];
                    $csvContent=$destinationPath.'/'.$newName;
                    $fileName = $doc_file->getClientOriginalName();
                   // print_r($csvContent);die();

                    $result = IntimationResponseMail::sendThrottled($reciepients , $mailData ,$csvContent, $fileName);
        
              }*/
 

      }
      
       if($import->getRowCount() == 0){

             $module = 'Insurance'; 
             $operation = 'Import';
             $note = 'Imported Lead Details - '.$import->getRowCount();
             $link = url('/').'/insurance/claim_forms';

            $this->auditlogs($module , $operation ,$note , $link);
            return redirect()->back()->with('message','0 rows imported');
        }
        else {

            $inserted = $import->getInsertedCount();
            $updated = $import->getUpdatedCount();

             $module = 'Insurance'; 
             $operation = 'Import';
             $note = 'Imported Lead Details - New Entry - '.$inserted.', Updated Entry - '.$updated;
             $link = url('/').'/insurance/claim_forms';

            $this->auditlogs($module , $operation ,$note , $link);

            // return redirect()->back()->with('message',$import->getRowCount().' row(s) imported. New Entry - '.$inserted.', Updated Entry - '.$updated);;
        }


        $failures = $import->getCollectedFailures();
       //  print_r(json_encode($failures));die();
       $Errordata = array();
       if (!empty($import->failedRows)) {
      // Send mail with error rows
        
        foreach ($import->failedRows as $key => $value) {
           $Errordata[]=$value['data'];
        }
     
         // return Excel::download(new ExportErrorRows($Errordata), 'insurance_error_leads_'.date('Y_m_d_his').'.xlsx');
          
      } 


      if (sizeof($failures) > 0 ) {
            return back()->with([
                'failures' => $failures,
                'errordata' => $Errordata,
                'message' => ' New Entry - '.$inserted.'   , Updated Entry - '.$updated.'   , Error rows - '.sizeof($Errordata)
            ]);
        }else{
           return redirect()->back()->with('message',$import->getRowCount().' row(s) imported. New Entry - '.$inserted.', Updated Entry - '.$updated);;
        }
       



    }


    public function downloadErrorReport(Request $request)
    {
        $json = base64_decode($request->input('data'));
        $Errordata = json_decode($json, true);

        return Excel::download(
            new ExportErrorRows($Errordata,'insurance_error_leads_'.date('Y_m_d_his').'.xlsx'),
            'error_report.xlsx'
        );
    }

    public function search(Request $request){
        $search=$request->search;

    }

    public function save_nominee_details(Request $request){
     // print_r($request->input());die();
     
      $inputdata = $request->all();
      $errors = [];

       $request->validate([
          'nominee_name_bank' => 'required',
          'acc_number' => ['nullable','max:18']
         
      ]);

      foreach ($inputdata as $key => $value) {
          if (is_string($value)) {
              if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $value)) {
                  $errors[$key] = 'Script tags are not allowed.';
              } elseif (!preg_match('/^[a-zA-Z0-9 ,._\/&-]+$/', $value)) {
                  $errors[$key] = 'Only letters, numbers, spaces, and , . - are allowed.';
              }
          }
      }

      if (!empty($errors)) {
          return redirect()->back()->withErrors($errors)->withInput();
      }

      /*$nominee_verified = '';

      if(!empty($request->nominee_name_bank) && !empty($request->bank_name) && !empty($request->acc_number) && !empty($request->ifsc) && !empty($request->branch_name) && !empty($request->nominee_number) ){

      }*/

       $insurancenomineedata=InsuranceNomineeDetail::where('insurance_claim_details_id',decrypt($request->lead_id))->first();
       if($insurancenomineedata) {
           $nomineedetail = InsuranceNomineeDetail::find($insurancenomineedata->id);

           if($nomineedetail->nominee_data_verified == 'No'){
                  $nomineedetail->nominee_data_verified = '';
           }

           if($nomineedetail->spdc_data_verified == 'No'){
                  $nomineedetail->spdc_data_verified = '';
           }
       }else{
         $nomineedetail = new InsuranceNomineeDetail;
         $nomineedetail->insurance_claim_details_id = decrypt($request->lead_id);
       }
 
       $nomineedetail->nominee_name_bank =$request->nominee_name_bank;
       $nomineedetail->bank_name =$request->bank_name;
       $nomineedetail->acc_number =$request->acc_number;
       $nomineedetail->ifsc =$request->ifsc;
       $nomineedetail->branch_name =$request->branch_name;
       $nomineedetail->spdc_bank_name =$request->spdc_bank_name;
       $nomineedetail->spdc_chk_no =$request->spdc_chk_no;
       $nomineedetail->courier_name =$request->courier_name;
       $nomineedetail->pod_no =$request->pod_no;
       $nomineedetail->nominee_number =$request->nominee_number;
      // $nomineedetail->cheq_sent_date =$request->cheq_sent_date;
       $nomineedetail->bo_remarks =$request->bo_remarks;
       /*$nomineedetail->bo_maker =$request->bo_maker;
       $nomineedetail->bo_checker =$request->bo_checker;*/
       $nomineedetail->spdc_rec_date = $request->spdc_rec_date;
       $nomineedetail->ack_rec_date = $request->ack_rec_date;
       $nomineedetail->pkt_no = $request->pkt_no;

       if(Auth::user()->branch_ic != '1100'){
          $nomineedetail->bo_maker = Auth::user()->employee_id;
       }
       
       $nomineedetail->save();

        if($nomineedetail->id !='' || $nomineedetail->id != 0){
             $claimdata = InsuranceClaimDetail::where('id',decrypt($request->lead_id))->first();

             $mailData=['message' => 'Nominee details are updated in Insurance Module.Please refer Lead ID - '.$claimdata->utrn.' to view detailed information'];
              $reciepients=array();
              $reciepients=['druva@netiapps.com'];
              $csvContent='';
              $fileName = '';
       // print_r($csvContent);die();

        $result = IntimationResponseMail::sendThrottled($reciepients , $mailData ,$csvContent, $fileName);

             $module = 'Insurance'; 
             $operation = 'Update';
             $note = 'Maker Updated Nominee Details - '.$claimdata->utrn;
             $link = url('/').'/insurance/view_claim_details/'.encrypt($request->lead_id);

            $this->auditlogs($module , $operation ,$note , $link);

            return redirect()->back()->with('success','Saved Succesfully');
        }
        else{
            return redirect()->back()->with('failure','Error while Saving data');
        }
    }

    public function save_documents(Request $request){
       $request->validate([
          'pdfs.*' => 'required|mimes:pdf|max:5120', // max 5MB each
          // Add any other validations for text fields here
       ]);

       $savedFiles = [];

       $leadDetails= InsuranceClaimDetail::where('id',decrypt($request->lead_id))->first();
       $folderName = $leadDetails->utrn;

       if (file_exists(public_path().'/template/Documents/'.$folderName)) {  
        } else {
          File::makeDirectory(public_path().'/template/Documents/'.$folderName, $mode = 0775, true, true);
        }
       
        $filepath = '/template/Documents/'.$folderName;

    
      if ($request->hasFile('pdfs')) {
        foreach ($request->file('pdfs') as $index => $file) {
            // Original filename
            $originalName = $file->getClientOriginalName();
            $tempPath = $file->getPathname();

            // Create a unique filename — use timestamp or UUID
            $newName = date('YmdHis') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

             $destination = public_path().$filepath . '/' . $newName;
       
            if (move_uploaded_file($tempPath, $destination)) {
              InsuranceDocument::create([
                'insurance_claim_details_id' => decrypt($request->lead_id),
                'original_name' => $originalName,
                'stored_name' => $newName,
                'filepath' => $filepath,
                'status' => '1',
                'creator'=> Auth::user()->employee_id,
                'updator' => Auth::user()->employee_id

              ]);
            }

        }
    }


    return response()->json(['message' => 'Saved Successfully']);
    }


    public function update_documents(Request $request){
      $removable_ids = [];

      foreach ($request->delete_doc_ids as $value) {
          $decoded = json_decode($value, true);
          if (is_array($decoded)) {
              $removable_ids = array_merge($removable_ids, $decoded);
          }
      }

       $request->validate([
          'pdfs.*' => 'required|mimes:pdf|max:5120', // max 5MB each
          // Add any other validations for text fields here
       ]);

       $savedFiles = [];
       $errors = [];

       if ($request->hasFile('pdfs')) {

            foreach ($request->file('pdfs') as $file) {
            // print_r("kkk");die();
                // 🔍 Validate each file
                $result = $this->validateFileWhileSaving($file);
               // print_r($result);die();
                if (strpos($result, 'Malicious content detected') !== false || 
                    strpos($result, 'Invalid file type') !== false || 
                    strpos($result, 'File size exceeds') !== false) {
                    $errors[] = $result;
                    continue; // skip saving this file
                }
              
            }

            if(sizeof($errors)>0){
               return response()->json([
                    'status' => 'false',
                    'message' => implode(',', $errors)
                ]);
            }
        }


       $leadDetails= InsuranceClaimDetail::where('id',decrypt($request->lead_id))->first();
       $folderName = $leadDetails->utrn;

       if (file_exists(public_path().'/template/Documents/'.$folderName)) {  
        } else {
          File::makeDirectory(public_path().'/template/Documents/'.$folderName, $mode = 0775, true, true);
        }
       
        $filepath = '/template/Documents/'.$folderName;

    
        if ($request->hasFile('pdfs')) {
          foreach ($request->file('pdfs') as $index => $file) {
              // Original filename
              $originalName = $file->getClientOriginalName();
              $tempPath = $file->getPathname();

              // Create a unique filename — use timestamp or UUID
              $newName = date('YmdHis') . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

               $destination = public_path().$filepath . '/' . $newName;
         
              if (move_uploaded_file($tempPath, $destination)) {
                InsuranceDocument::create([
                  'insurance_claim_details_id' => decrypt($request->lead_id),
                  'original_name' => $originalName,
                  'stored_name' => $newName,
                  'filepath' => $filepath,
                  'status' => '1',
                  'creator'=> Auth::user()->employee_id,
                  'updator' => Auth::user()->employee_id

                ]);
              }

          }
      }

      $mailData=['message' => 'Claim related documents are updated in Insurance Module.Please refer Lead ID - '.$leadDetails->utrn].' to view detailed information';
        $reciepients=array();
        $reciepients=['druva@netiapps.com'];
        $csvContent='';
        $fileName = '';
       // print_r($csvContent);die();

        $result = IntimationResponseMail::sendThrottled($reciepients , $mailData ,$csvContent, $fileName);

       $module = 'Insurance'; 
       $operation = 'Update';
       $note = 'Documents are updated - '."INS_CLM".$leadDetails->utrn;
       $link = url('/').'/insurance/view_claim_details/'.encrypt($leadDetails->id);

       $this->auditlogs($module , $operation ,$note , $link);
      
      InsuranceDocument::whereIn('id',$removable_ids)->update(['status' => '0', 'updator' => Auth::user()->employee_id]);

      return response()->json(['message' => "Updated Successfully"]);
    }

    public function save_claim_checklist(Request $request){
        // print_r(json_encode($request->name));die();
        $inputdata = $request->all();
        $errors = [];

        foreach ($inputdata as $key => $value) {
            if (is_string($value)) {
                if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $value)) {
                    $errors[$key] = 'Script tags are not allowed.';
                } elseif (!preg_match('/^[a-zA-Z0-9 ,._\/&-]+$/', $value)) {
                    $errors[$key] = 'Only letters, numbers, spaces, and , . - are allowed.';
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }
          $checklistDetails = InsuranceChecklist::where('insurance_claim_details_id',decrypt($request->claim_id))->first();
            if($checklistDetails){
             $checklist = InsuranceChecklist::find($checklistDetails->id);
          }else{
               $checklist = new InsuranceChecklist;
                $checklist->insurance_claim_details_id = decrypt($request->claim_id) ;             
          }

            $checklist->cust_id = $request->cust_id ;
            $checklist->branch_id = $request->branch_id ;
            $checklist->sent_date = $request->date.'-'.$request->month.'-'.$request->year ;
            $checklist->deceased_name = $request->deceased_name ;
            $checklist->name = json_encode($request->name) ;
            $checklist->name_mismatch = json_encode($request->name_mismatch) ;
            $checklist->age = json_encode($request->age) ;
            $checklist->age_mismatch = json_encode($request->age_mismatch) ;
            $checklist->customer_id = json_encode($request->customer_id) ;
            $checklist->dod = json_encode($request->dod) ;
            $checklist->is_mlc = json_encode($request->is_mlc) ;
            $checklist->fir_attached = json_encode($request->fir_attached) ;
            $checklist->death_certificate = json_encode($request->death_certificate) ;
            $checklist->valid_certificate = json_encode($request->valid_certificate) ;
            $checklist->doc_bajaj = json_encode($request->doc_bajaj) ;
            $checklist->doc_death = json_encode($request->doc_death) ;
            $checklist->doc_fir = json_encode($request->doc_fir) ;
            $checklist->doc_proof = json_encode($request->doc_proof) ;
            $checklist->doc_closure_request = json_encode($request->doc_closure_request) ;
            $checklist->doc_ecs = json_encode($request->doc_ecs) ;
            $checklist->docs_readable =json_encode($request->docs_readable) ;
            $checklist->nominee_name = $request->nominee_name ;
            $checklist->acc_no = $request->acc_no ;
            $checklist->bank_name = $request->bank_name ;
            $checklist->micr = $request->micr ;
            $checklist->ifsc = $request->ifsc ;
            $checklist->branch = $request->branch ;
            $checklist->bo_maker_emp = $request->bo_maker_emp ;
            $checklist->bo_maker_name = $request->bo_maker_name ;
            $checklist->bo_maker_sign = $request->bo_maker_sign ;
            $checklist->bo_maker_date = $request->bo_maker_date ;
            $checklist->bo_checker_emp = $request->bo_checker_emp ;
            $checklist->bo_checker_name = $request->bo_checker_name ;
            $checklist->bo_checker_sign = $request->bo_checker_sign ;
            $checklist->bo_checker_date = $request->bo_checker_date ;
            $checklist->ho_maker_emp = $request->ho_maker_emp ;
            $checklist->ho_maker_name = $request->ho_maker_emp ;
            $checklist->ho_checker_emp = $request->ho_checker_emp ;
            $checklist->ho_checker_name = $request->ho_checker_emp ; 

            $checklist->save();
       

        if($checklist->id !='' || $checklist->id != 0){

            $claimdata = InsuranceClaimDetail::where('id',decrypt($request->claim_id))->first();

             $module = 'Insurance'; 
             $operation = 'Update';
             $note = 'Checklist Updated - '.$claimdata->utrn;
             $link = url('/').'/insurance/view_claim_details/'.encrypt($request->claim_id);

            $this->auditlogs($module , $operation ,$note , $link);

            return redirect()->back()->with('success','Saved Succesfully');
        }
        else{
            return redirect()->back()->with('failure','Error while Saving data');
        }
    }

    function getFinancialYearMonths($year = null) {
        $months = [];
       // print_r($year);die();
        // If no year is passed, determine based on current date
        if (!$year) {
            $currentMonth = date('n'); // Numeric month without leading zeros
            $currentYear = date('Y');
            
            if ($currentMonth >= 4) {
                $startYear = $currentYear;
            } else {
                $startYear = $currentYear - 1;
            }
        } else {
            $startYear = $year;
        }

        // Start from April of startYear to March of next year
        for ($i = 0; $i < 12; $i++) {
            $month = date('Y-m', strtotime("+$i months", strtotime("$startYear-04-01")));
            $months[] = $month;
        }

        return $months;
    }

    public function audit(Request $request){
   // print_r($request->input());die();
      if($request->search == ''){
        $search='';
      }else{
        $search=$request->search;
      }

      if(!isset($request->start)){
         $start_date = date('Y-m-').'01 00:00:01';
         $end_date =  date('Y-m-d').' 23:59:59';

      }
      else{
         $start_date = $request->start.' 00:00:01';
         $end_date = $request->end.' 23:59:59';

      }

      if($request->type == 'filter' || !isset($request->type) ){
          $data = AuditLog::whereBetween('created_at',[$start_date , $end_date])
          ->when($search,function($query)use($search){
               $query->where('note','LIKE','%'.$search.'%');
               $query->orWhere('user_id','LIKE','%'.$search.'%');
               $query->orWhere('operation','LIKE','%'.$search.'%');
          })
          ->orderBy('id','DESC')->paginate(50);
          return view('insurance.audit',compact('data','search','start_date','end_date'));
      }
      else{
        $data = AuditLog::whereBetween('created_at',[$start_date , $end_date])
          ->when($search,function($query)use($search){
               $query->where('note','LIKE','%'.$search.'%');
               $query->orWhere('user_id','LIKE','%'.$search.'%');
               $query->orWhere('operation','LIKE','%'.$search.'%');
          })
          ->orderBy('id','DESC')->get();

        
         return Excel::download(new ExportAuditLogs($data), 'audit_logs_'.date('Ymdhis').'.xlsx'); 

      }
      
    }

    public function report(Request $request){
    //print_r($request->input());die();

      $currentYear = date('Y');
      $currentMonth = date('m');

      if ($currentMonth >= 4) {
          $startYear = $currentYear;
      } else {
          $startYear = $currentYear - 1;
      }
      
      if($request->start == ''){
        $start = $startYear.'-04-01';
        $end=date('Y-m-d');
      }else{
        $start = $request->start;
        $end= $request->end;
      }
    //  print_r($start);die();
      $search= $request->search;
      $region= $request->region;
      $partner= $request->partner;
      $product= $request->product;
      $claim_status= $request->status;
      $proccesed= $request->proccesed;
      $branch= $request->branch;
      if(Auth::user()->branch_id != '1100'){
        $branch=Auth::user()->branch_id;
      }
      
    // print_r($branch);die();
      $data = InsuranceClaimDetail::whereBetween('intimation_date',[$start , $end])
              ->with('nominee')
              ->when($region,function($q)use($region){
                 $q->where('region',$region);
              })
              ->when($partner,function($q)use($partner){
                 $q->where('partner',$partner);
              })
              ->when($search,function($q)use($search){
                 $q->where('utrn','LIKE','%'.$search.'%');
                 $q->orWhere('policy_number','LIKE','%'.$search.'%');
                 $q->orWhere('cust_id','LIKE','%'.$search.'%');
                 $q->orWhere('load_acc_id','LIKE','%'.$search.'%');
                 $q->orWhere('mp_no','LIKE','%'.$search.'%');
                 $q->orWhere('intimation_date','LIKE','%'.$search.'%');
                 $q->orWhere('notification_number','LIKE','%'.$search.'%');
                 $q->orWhere('load_acc_id','LIKE','%'.$search.'%');
                 $q->orWhere('mp_no','LIKE','%'.$search.'%');
              })
              ->when($product,function($q)use($product){
                 $q->where('product',$product);
              })
              ->when($claim_status,function($q)use($claim_status){
                 $q->where('cliam_status',$claim_status);
              })
              ->when($proccesed,function($q)use($proccesed){
                 $q->where('processed_by',$proccesed);
              })
              ->when($branch,function($q)use($branch){
                 $q->where('branch',$branch);
              })
              ->with('additionalfields')
              ->orderBy('id','DESC')->get();

    // print_r(json_encode($data));die();
     if(!isset($request->action) || $request->action == 'filter'){
      
        $partners = InsurancePartner::get();
        $products = InsuranceProduct::get();
        $claimstatus = InsuranceClaimStatus::get();
        $rlStat=InsuranceRequestLetterStatus::get();
        $procesedby=['NA','Vindhya','Ujjivan','HO']; 

        $brID = Auth::user()->branch_id;

        if(Auth::user()->branch_id == '1100'){
          $branches = Branch::get();
        }else{
          $branches = Branch::where('code',$brID)->get();
        } 
        

        $start = $request->start;
        $end = $request->end;

      return view('insurance.report',compact('data','partners','products','claimstatus','rlStat','procesedby','start','end','region','branch','partner','product','claim_status','proccesed','search','branches'));
     }
     else{
       
         $module = 'Insurance'; 
         $operation = 'Export';
         $note = 'Report generated';
         $link = url('/').'/insurance/leads-report/';

         $this->auditlogs($module , $operation ,$note , $link);

         $additionl_fileds = AdditionalFieldSetting::get();

         return Excel::download(new ExportInsuranceLeads($data,$additionl_fileds), 'insurance_leads_'.date('Ymdhis').'.xlsx');
     }

    }

    public function download_checklist($id){

        /*$formname='checklist';
        $checklistDetails = InsuranceChecklist::where('insurance_claim_details_id',decrypt($id))->first();
        $pdf = PDF::loadView('insurance.templates.'.$formname, ['data'=> $checklistDetails])->setPaper('A4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
        return $pdf->stream('Checklist.pdf');
       // return $pdf->download($partner.'_'.$claimdata->cust_id.'.pdf');

             $module = 'Insurance'; 
             $operation = 'Update';
             $note = 'Downladed Checklist - '.$checklistDetails->leadDetails->utrn;
             $link = url('/').'/insurance/view_claim_details/'.encrypt($claimdata->id);

            $this->auditlogs($module , $operation ,$note , $link);*/



             $this->downloadFolderSmart($partner_type->folder_name); 

          return response()->download(public_path('/template/checklist.pdf'));  

    }

    public function downloadClaim(Request $request)
    {
        $claim = InsuranceClaimDetail::findOrFail($request->claim_id);

        $module = 'Insurance';
        $operation = 'Claim Form';
        $note = 'Downloaded/Viewed Claim Form - ' . $claim->utrn;
        $link = url('/insurance/view_claim_details/' . encrypt($claim->id));

        // Your custom method to save the audit log
        $this->auditlogs($module, $operation, $note, $link);

        return response()->json(['status' => 'success']);
    }

    public function downloadChecklist(Request $request)
    {
        $claim = InsuranceClaimDetail::findOrFail($request->claim_id);

        $module = 'Insurance';
        $operation = 'Checklist';
        $note = 'Downloaded/Viewed Checklist - ' . $claim->utrn;
        $link = url('/insurance/view_claim_details/' . encrypt($claim->id));

        // Your custom method to save the audit log
        $this->auditlogs($module, $operation, $note, $link);

        return response()->json(['status' => 'success']);
    }

    public function settings(){

        $partners = InsurancePartner::get();
        $products = InsuranceProduct::get();
        $placeofdeath = InsurancePlaceofDeath::get();
        $relationship = InsuranceRelationship::get();
        $deathcause = InsuranceCauseOfDeath::get();
        $claimstatus = InsuranceClaimStatus::get();
        $additionalfields = AdditionalFieldSetting::get();
        $region = Region::get();

        return view('insurance.settings',compact('partners','products','placeofdeath','relationship','deathcause','claimstatus' , 'region','additionalfields'));
    }

    public function add_new_insurance_item(Request $request){

     // print_r($request->input());die();
      $module = $request->modulename;

      $inputdata = $request->all();
      $errors = [];

      foreach ($inputdata as $key => $value) {
          if (is_string($value)) {
              if (preg_match('/<script\b[^>]*>(.*?)<\/script>/i', $value)) {
                  $errors[$key] = 'Script tags are not allowed.';
              } elseif (!preg_match('/^[a-zA-Z0-9 ,._\/&-]+$/', $value)) {
                  $errors[$key] = 'Only letters, numbers, spaces, and , . - are allowed.';
              }
          }
      }



      if (!empty($errors)) {
        //print_r($errors);die();
          return redirect()->back()->withErrors($errors)->withInput();
      }

     // print_r($request->input());die();
      if($module == 'Region'){
        Region::create(['name'=> $request->title]);
      }

      if($module == 'Partner'){
        InsurancePartner::create(['partner'=> $request->title]);
      }



      if($module == 'Product'){
        $folderName = "FOLD_".date('YmdHi');

        $errors = [];
        $uploadedFiles = [];

        if ($request->hasFile('files')) {

            foreach ($request->file('files') as $file) {
            // print_r("kkk");die();
                // 🔍 Validate each file
                $result = $this->validateFileWhileSaving($file);
               // print_r($result);die();
                if (strpos($result, 'Malicious content detected') !== false || 
                    strpos($result, 'Invalid file type') !== false || 
                    strpos($result, 'File size exceeds') !== false) {
                    $errors[] = $result;
                    continue; // skip saving this file
                }
              
            }

            if(sizeof($errors)>0){
              return redirect()->back()->with('failure',implode(',', $errors));
            }
        }
        else{
          return redirect()->back()->with('failure','Could not save data .  Please select Claim Forms');
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        if (file_exists(public_path().'/template/'.$folderName)) {  
          } else {
            File::makeDirectory(public_path().'/template/'.$folderName, $mode = 0775, true, true);
          }

           foreach($_FILES['files']['name'] as $key=>$val){ 
          
               $fileName = basename($_FILES['files']['name'][$key]); 
                 
              $destinationPath = public_path().'/template/'.$folderName.'/'.$fileName ;
              //print_r($fileName);die();
              move_uploaded_file($_FILES["files"]["tmp_name"][$key], $destinationPath);

          
            }


        InsuranceProduct::create(['product'=> $request->title ,'partner_id'=>$request->partner, 'type' => 'NMB' , 'folder_name' => $folderName]);
      }

      if($module == 'Cause of Death'){
        InsuranceCauseOfDeath::create(['cause'=> $request->title ]);
      }

      if($module == 'Place Of Death'){
        InsurancePlaceofDeath::create(['place'=> $request->title]);
      }

      if($module == 'Claim Status'){
        InsuranceClaimStatus::create(['claim_status'=> $request->title]);
      }

      if($module == 'new_field'){
       AdditionalFieldSetting::create([
          'field_name' => $request->title,
          'field_type' => 'input' ,
          'allowed_chars' => $request->allowed_chars,
          'module' => 'HO',
          'creator' => Auth::user()->employee_id]);
      }

        $mailData=['message' => 'New '.$module.' added to Insurance Module . - '.$request->title ];
        $reciepients=array();
        $reciepients=['druva@netiapps.com'];
        $csvContent='';
        $fileName = '';
       // print_r($csvContent);die();

        $result = IntimationResponseMail::sendThrottled($reciepients , $mailData ,$csvContent, $fileName);


        $module = 'Insurance';
        $operation = $module;
        $note = 'New '.$request->modulename.' added';
        $link = url('/insurance/settings/');

        $this->auditlogs($module, $operation, $note, $link);

      return redirect()->back()->with('success', "Added Successfully");

    }

    public function validateFileWhileSaving(\Illuminate\Http\UploadedFile $file)
    {
        $maliciousPatterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',
            '/<iframe\b[^>]*>/is',
            '/<\/iframe>/is',
            '/<base\b[^>]*>/is',
            '/href\s*=\s*["\']?(https?:\/\/[^\s"\'<>]+)["\']?/i',
            '/style\s*=\s*["\']?[^"\']*(?:width|height|background)[^"\']*["\']?/i',
            '/base64_decode\(/i',
            '/eval\(/i',
            '/shell_exec\(/i',
            '/phpinfo\(/i',
            '/system\(/i',
            '/\bfunction\s+[a-zA-Z0-9_]+\s*\(/i',
            '/\bvar\s+[a-zA-Z0-9_]+\s*=\s*function\s*\(/i',
            '/\bconst\s+[a-zA-Z0-9_]+\s*=\s*\(\s*\)\s*=>/i',
            '/\bon[a-z]{3,16}\s*=\s*["\']?\s*[^"\']*\s*\(?\s*\)?/i'
        ];

        $fileName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['pdf'];
        $maxSize = 40 * 1024 * 1024; // 40MB

        if (!in_array($extension, $allowedExtensions)) {
            return "$fileName: Invalid file type.";
        }

        if ($file->getSize() > $maxSize) {
            return "$fileName: File size exceeds 40MB.";
        }

        $isValidExtension = self::isValidFileExtension($fileName, $allowedExtensions);
         if (!$isValidExtension) {
            
            return true;
        } 

        if ($extension === 'pdf') {

            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($file->getPathname());
                $text = $pdf->getText();

                if (empty($text)) {
                   // return "$fileName: Corrupt PDF or unreadable content.";
                }

                $fileContent = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                foreach ($maliciousPatterns as $pattern) {
                    if (preg_match($pattern, $fileContent)) {

                        return "$fileName: Malicious content detected.";
                    }
                }
            } catch (\Exception $e) {
                //return "$fileName: PDF extraction failed.";
            }
        }

        if ($extension === 'docx') {
            $content = '';
            $zip = new \ZipArchive();
            if ($zip->open($file->getPathname()) === true) {
                if (($index = $zip->locateName('word/document.xml')) !== false) {
                    $xmlData = $zip->getFromIndex($index);
                    $xml = new \SimpleXMLElement($xmlData);
                    $content = strip_tags($xml->asXML());
                }
                $zip->close();
            }

            $fileContent = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            foreach ($maliciousPatterns as $pattern) {
                if (preg_match($pattern, $fileContent)) {

                    return "$fileName: Malicious content detected.";
                }
            }
        }

        return "$fileName: File is clean.";
    }

    public static function isValidFileExtension($fileName, $allowedExtensions)
    {
        // Extract the actual extension (last part)
        $fileParts = explode('.', $fileName);
        
        // Ensure there's at least one dot
        if (count($fileParts) < 2) {
            return false;
        }

        // Get the last part as the extension
        $fileExtension = strtolower(array_pop($fileParts));

        // Validate the last extension
        if (!in_array($fileExtension, $allowedExtensions)) {
            return false; // Invalid extension
        }

        // Reconstruct the filename without the extension
        $baseName = implode('.', $fileParts); 

        // Check if the base name contains another valid extension
        foreach ($allowedExtensions as $ext) {
            if (preg_match('/\b' . preg_quote($ext, '/') . '\b/i', $baseName)) {
                // echo 'ERR';exit;
                return false; // Found another valid extension earlier, reject file
            }
        }
        return true; // Valid file
    }


    public function get_products(Request $request){
       $partner = InsurancePartner::where('partner',$request->partner_id)->first();
       $products = InsuranceProduct::where('partner_id', $partner->id)->pluck('product', 'id');
       return response()->json($products);
    }

    public function clone_lead_details($id){
        $lead = InsuranceClaimDetail::find($id);
        if ($lead) {
            $utrn = rand('000000','999999');

            $nomineedetail=InsuranceNomineeDetail::where('insurance_claim_details_id',$id)->first();
            
              $newLead = $lead->replicate(); // Clone attributes except the primary key
              $newLead->utrn = "INS_CLM".$utrn;
              $newLead->save(); // Inserts as a new row with a new id

              $newNomineeDetails = $nomineedetail->replicate();
              $newNomineeDetails->insurance_claim_details_id = $newLead->id;
              $newNomineeDetails->save();


              $module = 'Insurance';
              $operation = 'Clone';
              $note = 'New lead created '.$newLead->utrn.' by cloning - '.$lead->utrn;
              $link = url('/').'/insurance/view_claim_details/'.encrypt($newLead->id);

              $this->auditlogs($module, $operation, $note, $link);

              return redirect()->back()->with('success','Lead cloned Succesfully. New lead ID - '.$newLead->utrn );
          } else {
              return redirect()->back()->with('failure','Lead details not found');
          }
                  
        }

        public function verify_nominee_details(Request $request){

              InsuranceNomineeDetail::where('id',$request->nominee_id)->update([
                'nominee_data_verified' => ($request->action == 'Accepted') ? 'Yes':'No',
                'nominee_checker_comments' => $request->nominee_remarks,
                'nominee_data_verifier' => Auth::user()->employee_id,
                'bo_checker' => Auth::user()->employee_id
              ]); 

              $nomineeData =InsuranceNomineeDetail::where('id',$request->nominee_id)->first();
              $leadData = InsuranceClaimDetail::where('id',$nomineeData->insurance_claim_details_id)->first();


              $module = 'Insurance';
              $operation = 'Nominee Details Verification';
              $note = 'Checker updated Nominee details consent for Lead ID - '.$leadData->utrn .' - Consent:'.$request->action . (($request->action != 'Accepted') ? '. Remarks : '.$request->nominee_remarks : '' );
              $link = url('/').'/insurance/view_claim_details/'.encrypt($leadData->id);

              $this->auditlogs($module, $operation, $note, $link);

              return redirect()->back()->with('success','Thank You . Your consent has been updated' );
        }

        public function verify_pod_details(Request $request){
              InsuranceNomineeDetail::where('id',$request->nominee_id)->update([
                'spdc_data_verified' => ($request->action == 'Accepted') ? 'Yes':'No',
                'spdc_checker_comments' => $request->pod_remarks,
                'spdc_data_verfier' => Auth::user()->employee_id,
                'bo_checker' => Auth::user()->employee_id
              ]); 

               $nomineeData =InsuranceNomineeDetail::where('id',$request->nominee_id)->first();
             

              $leadData = InsuranceClaimDetail::where('id',$nomineeData->insurance_claim_details_id)->update([
                'cliam_status' => ($request->action == 'Accepted') ? 'Document Sent to HO to Process' : 'Pending From Branch']);


              $leadData = InsuranceClaimDetail::where('id',$nomineeData->insurance_claim_details_id)->first();


              $module = 'Insurance';
              $operation = 'POD Details Verification';
              $note = 'Checker updated SPDC/POD details consent for Lead ID - ' . $leadData->utrn .' - Consent: ' . $request->action .
                     (($request->action != 'Accepted') ? '. Remarks : ' . $request->pod_remarks : '');

              $link = url('/').'/insurance/view_claim_details/'.encrypt($leadData->id);

             
              $this->auditlogs($module, $operation, $note, $link);

              return redirect()->back()->with('success','Thank You . Your consent has been updated' );
        }


}
