<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
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
use App\Imports\ImportClaimDetails;
use App\Exports\ExportInsuranceLeads;
use App\Models\AuditLog;
use App\AuditLogTrait;
use File;
use Excel;
use Auth;
use ZipArchive;


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
        
         $start = $fy[0].'-04-01 00:00:01';
         $end = $fy[1].'-03-31 23:59:59';

        if(Auth::user()->branch_id == '1100'){
          $total = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->count();
          $claimed = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->count();
          $pending_at_branch = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->whereIn('cliam_status',['Pending From Branch','Pending from branch-Require additional documents'])->count();
          $doc_at_ho = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Document Sent to HO to Process')->count();
          $inprogress = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->whereIn('cliam_status',['Pending from Insurance Company'])->count();
          
          foreach ($partners as $key => $value) {
              $partnerArray[]=$value->partner;
              $partnerCountArray[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('partner',$value->partner)->count();
          }

          $partnerChart = ['names' => $partnerArray , 'counts' => $partnerCountArray];

          
          foreach ($region as $val) {
              $claimedArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('region',$val)->where('cliam_status','Completed')->count();
              $noneligibleArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('region',$val)->whereIn('cliam_status',['Not Eligible','Not Eligible [Having outstanding]'])->count();
              $rejectedArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('region',$val)->where('cliam_status','Rejected')->count();
              $inprogressArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('region',$val)->whereNotIn('cliam_status',['Completed','Not Eligible','Not Eligible [Having outstanding]','Rejected',])->count();
          } 

          $regionChart = [$claimedArray ,$noneligibleArray , $rejectedArray , $inprogressArray]; 

          $monthArray=$this->getFinancialYearMonths();
          
          foreach ($monthArray as $vals) {
              $claimedAmount[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('claim_amount');
              $settledAmount[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('payable_to_nominee');
              $claimcount[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->count();


          }
          
          $claimchart=[$claimcount , $settledAmount , $claimedAmount];

          $causeofdeath =  InsuranceCauseOfDeath::all();

          foreach ($causeofdeath as $key => $value) {
             $deathcounts[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->where('cause_of_death',$value->cause)->count();
             $causenames[]=$value->cause;
          }

          $deathcausehart = [ $causenames , $deathcounts];

          $count10 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[0,10])->count();
          $count20 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[11,20])->count();
          $count30 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[21,30])->count();
          $count40 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[31,40])->count();
          $count50 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[41,50])->count();
          $count60 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[51,60])->count();
          $count70 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[61,70])->count();
          $count80 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[71,80])->count();
          $count90 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[81,90])->count();
          $count100 = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[91,100])->count();

          $deathagegroup = [$count10,$count20,$count30,$count40,$count50,$count60,$count70,$count80,$count90,$count100];

        } 
        else{
        
          $total = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->count();
          $claimed = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('cliam_status','Completed')->count();
          $pending_at_branch = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->whereIn('cliam_status',['Pending From Branch','Pending from branch-Require additional documents'])->count();
          $doc_at_ho = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('cliam_status','Document Sent to HO to Process')->count();
          $inprogress = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->whereIn('cliam_status',['Pending from Insurance Company'])->count();

           foreach ($partners as $key => $value) {
              $partnerArray[]=$value->partner;
              $partnerCountArray[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('partner',$value->partner)->count();
          }

          $partnerChart = ['names' => $partnerArray , 'counts' => $partnerCountArray];

          
          foreach ($region as $val) {
              $claimedArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->where('cliam_status','Completed')->count();
              $noneligibleArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->whereIn('cliam_status',['Not Eligible','Not Eligible [Having outstanding]'])->count();
              $rejectedArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->where('cliam_status','Rejected')->count();
              $inprogressArray[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('region',$val)->whereNotIn('cliam_status',['Completed','Not Eligible','Not Eligible [Having outstanding]','Rejected',])->count();
           } 

           $regionChart = [$claimedArray ,$noneligibleArray , $rejectedArray , $inprogressArray];  

           $monthArray=$this->getFinancialYearMonths();
          
          foreach ($monthArray as $vals) {
              $claimedAmount[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('claim_amount');
              $settledAmount[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->sum('payable_to_nominee');
              $claimcount[]=InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('intimation_date','LIKE',$vals.'%')->where('cliam_status','Completed')->count();
          }
          
          $claimchart=[$claimcount , $settledAmount , $claimedAmount];
          
          $causeofdeath =  InsuranceCauseOfDeath::all();
          foreach ($causeofdeath as $key => $value) {
             $deathcounts[] = InsuranceClaimDetail::whereBetween('created_at',[$start , $end])->where('branch',Auth::user()->branch_id)->where('cliam_status','Completed')->where('cause_of_death',$value->cause)->count();
             $causenames[]=$value->cause;
          }

          $deathcausehart = [ $causenames , $deathcounts];

          $count10 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[0,10])->count();
          $count20 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[11,20])->count();
          $count30 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[21,30])->count();
          $count40 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[31,40])->count();
          $count50 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[41,50])->count();
          $count60 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[51,60])->count();
          $count70 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[61,70])->count();
          $count80 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[71,80])->count();
          $count90 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[81,90])->count();
          $count100 = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereBetween('created_at',[$start , $end])->where('cliam_status','Completed')->whereBetween('age',[91,100])->count();

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
          })
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
                  ->orderBy('id','DESC')->paginate(25); 
        }
        
        return view('insurance.index',compact('data','search'));
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

        $procesedby=['NA','Vindhya','HO'];  
        $deceased=['APPLICANT','CO-APPLICANT','SPOUSE','CUSTOMER'];

        return view('insurance/create',compact('partners','products','placeofdeath','relationship','deathcause','claimstatus','procesedby','rlStat','deceased'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // print_r($request->input()); die();

    $request->validate([
        'region' => 'required',
        'branch' => 'required',
        'partner' => 'required',
        'product' => 'required',
        'region' => 'required',
        'policy_number' => 'required',
        'cust_id' => 'required',
        'actual_id' => 'required',
        'cliam_status' => 'required',
        'cause_of_death' => 'required',
        'deceased'=> 'required',
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
        $claimdata->re_submit_to_partner_date = $request->re_submit_to_partner_date;
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
            InsuranceNomineeDetail::create(['insurance_claim_details_id' => $claimdata->id]);
           // InsuranceChecklist::create(['insurance_claim_details_id' => $claimdata->id]);

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
        
        $deceased=['APPLICANT','CO-APPLICANT','SPOUSE','CUSTOMER'];
        $checklistdata = InsuranceChecklist::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
        $nomineedata = InsuranceNomineeDetail::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
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
        
      // print_r($formArray);die();
        return view('insurance.edit',compact('data','partners','products','placeofdeath','relationship','deathcause','claimstatus','procesedby','rlStat','deceased','checklistdata','nomineedata','formArray'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
          'region' => 'required',
          'branch' => 'required',
          'partner' => 'required',
          'product' => 'required',
          'region' => 'required',
          'policy_number' => 'required',
          'cust_id' => 'required',
          'actual_id' => 'required',
          'cliam_status' => 'required',
          'cause_of_death' => 'required',
          'deceased'=> 'required'
      ]);

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
        $claimdata->re_submit_to_partner_date = $request->re_submit_to_partner_date;
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
        $claimdata->bounced_chq_no = $request->bounced_chq_no;
        $claimdata->bounced_chq_date = $request->bounced_chq_date;
        $claimdata->bounced_chq_reason = $request->bounced_chq_reason;

        $claimdata->chq_deposit_date = $request->chq_deposit_date;
        $claimdata->recovered_amount = $request->recovered_amount;
        
        $claimdata->write_off_rec = $request->write_off_rec;
        $claimdata->write_off_status = $request->write_off_status;
        $claimdata->handed_to_bh = $request->handed_to_bh;
        $claimdata->handed_to_credit = $request->handed_to_credit;
        $claimdata->ho_employee_id = Auth::user()->employee_id;
        $claimdata->latest_editor = Auth::user()->employee_id;

        $claimdata->save();

        if($claimdata->id !='' || $claimdata->id != 0){
            InsuranceNomineeDetail::create(['insurance_claim_details_id' => decrypt($id) ]);
            InsuranceChecklist::create(['insurance_claim_details_id' => decrypt($id) ]);

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

          if($claimdata->partner == 'ABSLI'){
              $path = public_path('insurance_images/birlalogo.png');
              $partner = 'BIRLA';
              $formname='birlagroup';
          }

          if($claimdata->partner == 'HDFC'){
              $path = public_path('insurance_images/hdfc.png');
              $partner = 'HDFC';
              $formname='hdfc';
          }

          if($claimdata->partner == 'Max Life'){
              $path = public_path('insurance_images/maxlife.png');
              $partner = 'MAXLIFE';
              $formname='maxlife';
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
          return $pdf->stream($partner.'_MB_'.$claimdata->utrn.'.pdf');
         // return $pdf->download($partner.'_'.$claimdata->cust_id.'.pdf');

         }
         else if($partner_type->type == 'NMB'){
             $this->downloadFolderSmart($partner_type->folder_name);      
          }
          else{
         
          }

         $module = 'Insurance'; 
         $operation = 'Update';
         $note = 'Downladed Claim Form - '.$claimdata->utrn;
         $link = url('/').'/insurance/view_claim_details/'.encrypt($claimdata->id);

        $this->auditlogs($module , $operation ,$note , $link);


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

       Excel::import($import, $request->file('file'));

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
             return redirect()->back()->with('message',$import->getRowCount().' row(s) imported. New Entry - '.$inserted.', Updated Entry - '.$updated);;
        }
    }

    public function search(Request $request){
        $search=$request->search;

    }

    public function save_nominee_details(Request $request){
      // print_r($request->input());die();
       $insurancenomineedata=InsuranceNomineeDetail::where('insurance_claim_details_id',decrypt($request->lead_id))->first();
       if($insurancenomineedata) {
           $nomineedetail = InsuranceNomineeDetail::find($insurancenomineedata->id);
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
       $nomineedetail->bo_maker =$request->bo_maker;
       $nomineedetail->bo_checker =$request->bo_checker;
       $nomineedetail->spdc_rec_date = $request->spdc_rec_date;
       $nomineedetail->ack_rec_date = $request->ack_rec_date;
       $nomineedetail->pkt_no = $request->pkt_no;

       $nomineedetail->save();

        if($nomineedetail->id !='' || $nomineedetail->id != 0){
             $claimdata = InsuranceClaimDetail::where('id',decrypt($request->lead_id))->first();

             $module = 'Insurance'; 
             $operation = 'Update';
             $note = 'Updated Nominee Details - '.$claimdata->utrn;
             $link = url('/').'/insurance/view_claim_details/'.encrypt($request->lead_id);

            $this->auditlogs($module , $operation ,$note , $link);

            return redirect()->back()->with('success','Saved Succesfully');
        }
        else{
            return redirect()->back()->with('failure','Error while Saving data');
        }
    }

    public function save_claim_checklist(Request $request){
        // print_r(json_encode($request->name));die();
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

      if($request->search == ''){
        $search='';
      }else{
        $search=$request->search;
      }
      $data = AuditLog::when($search,function($query)use($search){
           $query->where('note','LIKE','%'.$search.'%');
           $query->orWhere('user_id','LIKE','%'.$search.'%');
           $query->orWhere('operation','LIKE','%'.$search.'%');
      })
      ->orderBy('id','DESC')->paginate(50);

      return view('insurance.audit',compact('data','search'));
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
              
              ->orderBy('id','DESC')->get();

    // print_r(json_encode($data));die();
     if(!isset($request->action) || $request->action == 'filter'){
      
        $partners = InsurancePartner::get();
        $products = InsuranceProduct::get();
        $claimstatus = InsuranceClaimStatus::get();
        $rlStat=InsuranceRequestLetterStatus::get();
        $procesedby=['NA','Vindhya','Ujjivan','HO'];  

        $start = $request->start;
        $end = $request->end;

      return view('insurance.report',compact('data','partners','products','claimstatus','rlStat','procesedby','start','end','region','branch','partner','product','claim_status','proccesed','search'));
     }
     else{
       
        return Excel::download(new ExportInsuranceLeads($data), 'insurance_leads_'.date('Ymdhis').'.csv');
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

          return response()->download(public_path('/template/checklist.pdf'));  

    }
}
