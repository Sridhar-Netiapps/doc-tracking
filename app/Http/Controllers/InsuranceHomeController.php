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
use Auth;
use Excel;

class InsuranceHomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('insurance.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function list()
    {
        $data = InsuranceClaimDetail::orderBy('id','DESC')->paginate(25);
        return view('insurance.index',compact('data'));
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
        return view('insurance/create',compact('partners','products','placeofdeath','relationship','deathcause','claimstatus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       // print_r($request->input()); die();
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
        $claimdata->ho_employee_id = Auth::user()->employee_id;
        $claimdata->latest_editor = Auth::user()->employee_id;

        $claimdata->save();

        if($claimdata->id !='' || $claimdata->id != 0){
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
        return view('insurance.edit',compact('data','partners','products','placeofdeath','relationship','deathcause','claimstatus'));
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
        //
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
}
