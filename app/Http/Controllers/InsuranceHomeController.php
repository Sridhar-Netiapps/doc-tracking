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
use Excel;
use Auth;

class InsuranceHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        if(Auth::user()->branch_id == '1100'){
          $total = InsuranceClaimDetail::count();
          $claimed = InsuranceClaimDetail::where('cliam_status','7')->count();
          $noneligible = InsuranceClaimDetail::whereIn('cliam_status',[8,9])->count();
          $rejected = InsuranceClaimDetail::where('cliam_status','11')->count();
          $inprogress = InsuranceClaimDetail::whereNotIn('cliam_status',[7,8,9,11,])->count();
          
          foreach ($partners as $key => $value) {
              $partnerArray[]=$value->partner;
              $partnerCountArray[]=InsuranceClaimDetail::where('partner',$value->id)->count();
          }

          $partnerChart = ['names' => $partnerArray , 'counts' => $partnerCountArray];

          
          foreach ($region as $val) {
              $claimedArray[] = InsuranceClaimDetail::where('region',$val)->where('cliam_status','7')->count();
              $noneligibleArray[] = InsuranceClaimDetail::where('region',$val)->whereIn('cliam_status',[8,9])->count();
              $rejectedArray[] = InsuranceClaimDetail::where('region',$val)->where('cliam_status','11')->count();
              $inprogressArray[] = InsuranceClaimDetail::where('region',$val)->whereNotIn('cliam_status',[7,8,9,11,])->count();
          } 

          $regionChart = [$claimedArray ,$noneligibleArray , $rejectedArray , $inprogressArray]; 

          $monthArray=$this->getFinancialYearMonths();
          
          foreach ($monthArray as $vals) {
              $claimedAmount[]=InsuranceClaimDetail::where('intimation_date','LIKE',$vals.'%')->where('cliam_status','7')->sum('claim_amount');
              $settledAmount[]=InsuranceClaimDetail::where('intimation_date','LIKE',$vals.'%')->where('cliam_status','7')->sum('payable_to_nominee');
              $claimcount[]=InsuranceClaimDetail::where('intimation_date','LIKE',$vals.'%')->where('cliam_status','7')->count();
          }
          
          $claimchart=[$claimcount , $settledAmount , $claimedAmount];

        } 
        else{
          $total = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->count();
          $claimed = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->where('cliam_status','7')->count();
          $noneligible = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereIn('cliam_status',[8,9])->count();
          $rejected = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->where('cliam_status','11')->count();
          $inprogress = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->whereNotIn('cliam_status',[7,8,9,11,])->count();

           foreach ($partners as $key => $value) {
              $partnerArray[]=$value->partner;
              $partnerCountArray[]=InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->where('partner',$value->id)->count();
          }

          $partnerChart = ['names' => $partnerArray , 'counts' => $partnerCountArray];

          
          foreach ($region as $val) {
              $claimedArray[] = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->where('region',$val)->where('cliam_status','7')->count();
              $noneligibleArray[] = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->where('region',$val)->whereIn('cliam_status',[8,9])->count();
              $rejectedArray[] = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->where('region',$val)->where('cliam_status','11')->count();
              $inprogressArray[] = InsuranceClaimDetail::where('branch',Auth::user()->branch_id)->where('region',$val)->whereNotIn('cliam_status',[7,8,9,11,])->count();
           } 

           $regionChart = [$claimedArray ,$noneligibleArray , $rejectedArray , $inprogressArray];  

         }

       // print_r(json_encode($regionChart));die();
        return view('insurance.dashboard',compact('total','claimed','noneligible','rejected','inprogress','partnerChart','regionChart','claimchart','claimedAmount'));
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
          })
          ->orderBy('id','DESC')->paginate(25);
        
        }else{
           $data = InsuranceClaimDetail::where('branch','LIKE',Auth::user()->branch_id.'%')
              ->when($search,function($query)use($search){
                   $query->where('cust_id','LIKE',$search.'%');
                   $query->orWhere('load_acc_id','LIKE',$search.'%');
                   $query->orWhere('utrn','LIKE','%'.$search.'%');
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

        $procesedby=['NA','Vindhya','Ujjivan','HO'];  
        $deceased=['CO-APPLICANT','SPOUSE','CUSTOMER'];

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
        $claimdata->cust_id = $request->cust_id;
        $claimdata->actual_id = $request->actual_id;
        $claimdata->deceased = $request->deceased;
        $claimdata->cause_of_death = $request->cause_of_death;
        $claimdata->cliam_status = $request->cliam_status;
        $claimdata->rl_status = $request->rl_status;

        $claimdata->deceased_name = $request->deceased_name;
        $claimdata->mp_no = $request->mp_no;
        $claimdata->policy_covered_date = $request->policy_covered_date;
        $claimdata->loan_tenure = $request->loan_tenure;
        $claimdata->policy_expiry_date = $request->policy_expiry_date;
        $claimdata->date_of_death = $request->date_of_death;
        $claimdata->gender = $request->gender;
        $claimdata->intimation_date = $request->intimation_date;
        $claimdata->age = $request->age;
        $claimdata->place_of_death = $request->place_of_death;
        $claimdata->load_acc_id = $request->load_acc_id;
        $claimdata->claim_amount = $request->claim_amount;
        $claimdata->dob = $request->dob;
        $claimdata->cas_status = $request->cas_status;
        $claimdata->nominee_name = $request->nominee_name;
        $claimdata->relationship = $request->relationship;
        $claimdata->nominee_number = $request->nominee_number;
        $claimdata->loan_outstanding = $request->loan_outstanding;
        $claimdata->payable_to_nominee = $request->payable_to_nominee;
        $claimdata->ack_rec_date = $request->ack_rec_date;
        $claimdata->pkt_no = $request->pkt_no;
        $claimdata->processed_by = $request->processed_by;
        $claimdata->ho_remark = $request->ho_remark;
        $claimdata->doc_rec_date = $request->doc_rec_date;
        $claimdata->submit_to_partner_date = $request->submit_to_partner_date;
        $claimdata->ho_remark2 = $request->ho_remark2;
        $claimdata->re_submit_to_partner_date = $request->re_submit_to_partner_date;
        $claimdata->settlement_date = $request->settlement_date;
        $claimdata->neft_rejection_date = $request->neft_rejection_date;
        $claimdata->neft_rejection_reason = $request->neft_rejection_reason;
        $claimdata->final_settlement_date = $request->final_settlement_date;
        $claimdata->recovery_status = $request->recovery_status;
        $claimdata->bounced_chq_no = $request->bounced_chq_no;
        $claimdata->bounced_chq_date = $request->bounced_chq_date;
        $claimdata->bounced_chq_reason = $request->bounced_chq_reason;
        $claimdata->write_off_rec = $request->write_off_rec;
        $claimdata->write_off_status = $request->write_off_status;
        $claimdata->handed_to_bh = $request->handed_to_bh;
        $claimdata->handed_to_credit = $request->handed_to_credit;
        $claimdata->ho_employee_id = Auth::user()->employee_id;
        $claimdata->latest_editor = Auth::user()->employee_id;

        $claimdata->save();

        if($claimdata->id !='' || $claimdata->id != 0){
             InsuranceNomineeDetail::create(['insurance_claim_details_id' => $claimdata->id]);
            InsuranceChecklist::create(['insurance_claim_details_id' => $claimdata->id]);
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
        $procesedby=['NA','Vindhya','Ujjivan','HO'];
        
        $deceased=['CO-APPLICANT','SPOUSE','CUSTOMER'];
        $checklistdata = InsuranceChecklist::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
        $nomineedata = InsuranceNomineeDetail::where('insurance_claim_details_id',decrypt($id))->orderBy('id','DESC')->first();
       // print_r($checklistdata);die();

        return view('insurance.edit',compact('data','partners','products','placeofdeath','relationship','deathcause','claimstatus','procesedby','rlStat','deceased','checklistdata','nomineedata'));
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
        $claimdata->policy_number = $request->policy_number;
        $claimdata->cust_id = $request->cust_id;
        $claimdata->actual_id = $request->actual_id;
        $claimdata->deceased = $request->deceased;
        $claimdata->cause_of_death = $request->cause_of_death;
        $claimdata->cliam_status = $request->cliam_status;
        $claimdata->rl_status = $request->rl_status;

        $claimdata->deceased_name = $request->deceased_name;
        $claimdata->mp_no = $request->mp_no;
        $claimdata->policy_covered_date = $request->policy_covered_date;
        $claimdata->loan_tenure = $request->loan_tenure;
        $claimdata->policy_expiry_date = $request->policy_expiry_date;
        $claimdata->date_of_death = $request->date_of_death;
        $claimdata->gender = $request->gender;
        $claimdata->intimation_date = $request->intimation_date;
        $claimdata->age = $request->age;
        $claimdata->place_of_death = $request->place_of_death;
        $claimdata->load_acc_id = $request->load_acc_id;
        $claimdata->claim_amount = $request->claim_amount;
        $claimdata->dob = $request->dob;
        $claimdata->cas_status = $request->cas_status;
        $claimdata->nominee_name = $request->nominee_name;
        $claimdata->relationship = $request->relationship;
        $claimdata->nominee_number = $request->nominee_number;
        $claimdata->loan_outstanding = $request->loan_outstanding;
        $claimdata->payable_to_nominee = $request->payable_to_nominee;
        $claimdata->ack_rec_date = $request->ack_rec_date;
        $claimdata->pkt_no = $request->pkt_no;
        $claimdata->processed_by = $request->processed_by;
        $claimdata->ho_remark = $request->ho_remark;
        $claimdata->doc_rec_date = $request->doc_rec_date;
        $claimdata->submit_to_partner_date = $request->submit_to_partner_date;
        $claimdata->ho_remark2 = $request->ho_remark2;
        $claimdata->re_submit_to_partner_date = $request->re_submit_to_partner_date;
        $claimdata->settlement_date = $request->settlement_date;
        $claimdata->neft_rejection_date = $request->neft_rejection_date;
        $claimdata->neft_rejection_reason = $request->neft_rejection_reason;
        $claimdata->final_settlement_date = $request->final_settlement_date;
        $claimdata->recovery_status = $request->recovery_status;
        $claimdata->bounced_chq_no = $request->bounced_chq_no;
        $claimdata->bounced_chq_date = $request->bounced_chq_date;
        $claimdata->bounced_chq_reason = $request->bounced_chq_reason;
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
        $claimdata = InsuranceClaimDetail::where('id',decrypt($id))->first();
        
        if($claimdata->partner == '1'){
            $path = public_path('insurance_images/bajaj_logo.png');
            $partner = 'BAJAJ';
            $formname='bajaj';
        }

        if($claimdata->partner == '2'){
            $path = public_path('insurance_images/birlalogo.png');
            $partner = 'BIRLA';
            $formname='birlagroup';
        }

        if($claimdata->partner == '3'){
            $path = public_path('insurance_images/hdfc.png');
            $partner = 'HDFC';
            $formname='hdfc';
        }

        if($claimdata->partner == '4'){
            $path = public_path('insurance_images/maxlife.png');
            $partner = 'MAXLIFE';
            $formname='maxlife';
        }
        
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        $pdf = PDF::loadView('insurance.templates.'.$formname, ['base64' => $base64,'data'=> $claimdata])->setPaper('A4', 'portrait')
                ->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
        return $pdf->stream($partner.'_'.$claimdata->cust_id.'.pdf');
       // return $pdf->download($partner.'_'.$claimdata->cust_id.'.pdf');


    }

    public function import_claim_data(Request $request){
       $import = new ImportClaimDetails ;

       Excel::import($import, $request->file('file'));

       if($import->getRowCount() == 0){
            return redirect()->back();
        }
        else {
             return redirect()->back();
        }
    }

    public function search(Request $request){
        $search=$request->search;

    }

    public function save_nominee_details(Request $request){

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
       $nomineedetail->cheq_sent_date =$request->cheq_sent_date;
       $nomineedetail->bo_remarks =$request->bo_remarks;
       $nomineedetail->bo_maker =$request->bo_maker;
       $nomineedetail->bo_checker =$request->bo_checker;

       $nomineedetail->save();

        if($nomineedetail->id !='' || $nomineedetail->id != 0){
            return redirect()->back()->with('success','Saved Succesfully');
        }
        else{
            return redirect()->back()->with('failure','Error while Saving data');
        }
    }

    public function save_claim_checklist(Request $request){
        // print_r(json_encode($request->name));die();
            
            $checklist = new InsuranceChecklist;
            $checklist->insurance_claim_details_id = decrypt($request->claim_id) ;
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
}
