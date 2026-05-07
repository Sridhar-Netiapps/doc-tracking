@extends('layouts.insurance-app')
@section('content')

<div class="container">
<div class="d-flex py-4">
<label class="label-font-header">Insurance Lead Details- {{ $data->utrn }}</label>
        @php
          $checker = ['CRM','ACRM','BOM','BM','Branch Manager','Branch Operation Manager','Customer Relationship Manager','Assistant Customer Relationship Manager','Senior Branch Manager'];
          $maker = ['Cashier','CCR','Customer Care Representative','Customer Care Representative-URC'];
          $HO = ['Head Of Operations','National Manager-Banking Operations /Regional Operations Manager','Officer-Insurance and TPP Operations','Specialist-Insurance and TPP Operations','Manager-Insurance and TPP Operations'];
        @endphp
        
      <div class="ms-auto">
      @if($data->cliam_status !='Completed' && $data->cliam_status !='Not Eligible' && $data->cliam_status !='Not Eligible-Having outstanding' && $data->cliam_status !='Not Eligible-Not Insured' && $data->cliam_status !='Completed')
      @if($data->nominee->nominee_data_verified == 'Yes')
        @if($data->products->type == 'MB')
          <a target="_blank"  href="{{ route('download_claim_form',encrypt($data->id))}}" ><button class="btn btn-sm btn-danger btn-text p-2" id="btn_download_claim_form">Download Claim Form</button> </a>
       @else
    <button id="openFilesBtn" class="btn btn-sm btn-danger btn-text p-2" id="btn_download_NMB_claim_form">Download Claim Form</button>
                          @endif
     <a target="_blank" href="{{ URL::to('/')}}/template/checklist.pdf"><button class="btn btn-sm btn-info btn-text p-2" id="btnChecklist">Download Checklist</button> </a>

               @endif
                          

                        
               
               @if( (Auth::user()->branch_id == '1100' && $nomineedata->nominee_data_verified == 'Yes' && $nomineedata->spdc_data_verified == 'Yes' && $data->cliam_status != 'Pending From Branch') 
               ||(Auth::user()->branch_id == '1100' && $nomineedata->nominee_data_verified == '' && $nomineedata->spdc_data_verified == '') 
               || (Auth::user()->branch_id != '1100'  && str_contains(strtolower($data->cliam_status) ,'pending from branch') && ( in_array(auth::user()->hrmData->current_designation , $maker )|| auth::user()->hasRole('bo-maker') )  ) )
 <button class="btn btn-sm btn-warning btn-text p-2" id="editBtn">Edit</button> 
  @endif

@if(!empty($nomineedata->nominee_name_bank) && !empty($nomineedata->bank_name) && !empty($nomineedata->acc_number) && !empty($nomineedata->ifsc) && !empty($nomineedata->branch_name) && !empty($nomineedata->nominee_number) && empty($nomineedata->nominee_data_verified) && ( in_array(auth::user()->hrmData->current_designation, $checker ) || auth::user()->hasRole('bo-checker')) )
             <button type="button" class="btn btn-danger p-2" data-bs-toggle="modal" data-bs-target="#nomineeModal" data-bs-whatever="Region">Verify Nominee Details</button>
                 
@endif
            
@if(!empty($nomineedata->pod_no) && !empty($nomineedata->courier_name)  && empty($nomineedata->spdc_data_verified) && ( in_array(auth::user()->hrmData->current_designation , $checker )|| auth::user()->hasRole('bo-checker') ))
            
                            <button type="button" class="btn btn-success p-2" data-bs-toggle="modal" data-bs-target="#podModal" data-bs-whatever="Region">Verify POD Details</button>
                 
@endif

@endif
            
            @if(auth::user()->branch_id == '1100')
<a class="confirm-link" href="{{ route('clone_lead_details',$data->id)}}" ><button class="btn btn-sm btn-success btn-text p-2" id="btn_clone">Clone</button> </a>
@endif

@if($nomineedata->nominee_data_verified == 'No' && in_array(auth::user()->hrmData->current_designation , $maker ))
<button class="btn btn-sm btn-success btn-text p-2 btn_nominee_remarks" id="btn_nominee_remarks">Checker Remarks - Nominee Details</button> 
@endif

@if($nomineedata->spdc_data_verified == 'No' && in_array(auth::user()->hrmData->current_designation , $maker ))
<button class="btn btn-sm btn-success btn-text p-2 btn_pod_remarks" id="btn_pod_remarks">Checker Remarks - POD Details</button> 
@endif
<a href="{{ route('insurance_list')}}" ><button class="btn btn-sm btn-dark btn-text p-2" id="btn_go_back">Go Back</button> </a>
</div>
</div>

<div class="py-2">
<div class="row">
<div class="col-3">
<button class="form-control btn btn-sm btn-secondary btn-toggle p-2 card-design" id="ho"  value="ho">Head Office </button>
</div>
           
<div class="col-3">
<button class="form-control btn-secondary btn btn-sm btn-toggle p-2 card-design border border-white" id="bo"  value="bo">Branch Office </button>
</div>

<!--  <div class="col-3">
<button class="form-control btn-secondary btn btn-sm btn-toggle p-2 card-design border border-black" id="cl"  value="cl">Claim Documents </button>
</div> -->
</div>


@if(session('success'))
<script nonce='{{ env("CSP_NONCE") }}'>
   document.addEventListener('DOMContentLoaded', function () {
       setTimeout(function () {
           Swal.fire({
               title: 'Message',
               text: @json(session('success')),
               icon: 'success',
               confirmButtonText: 'OK',
               allowOutsideClick: false,
               allowEscapeKey: false
           }).then((result) => {
               console.log('result:', result);
               
           });
       }, 300); // Delay to ensure full render
   });
</script>
@php
   session()->forget('success');
@endphp
@endif
        
       
@if(Session::has('failure'))
<script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
 var mesage = '{{ session('failure') }}';
 Swal.fire({
       title: 'Message',
       text: mesage,
       icon: 'failure',  
       confirmButtonText: 'OK'
   });
</script>
 
@endif 

        
<div class="py-3 d-block" id="head_off">
   <form method="POST" action="{{route('update_claim_details',encrypt($data->id))}}" disabled> 
   @csrf 
   <fieldset disabled>

   <div class="card mt-3">
        <div class="card-header label-font-header border-dark bg-card-header text-white">Lead Information</div>
        <div class="card-body">
        <div class="row">
       
        <div class="col">
        <div class="row">
        <div class="col text-end">
        <label>Created by </label><br>
        <label>Modified by</label><br>
        <label>POD Number</label>
        
        </div>
        <div class="col">
        <strong>{{ $data->hocreator->first_name ?? ''}} {{ $data->hocreator->last_name ?? ''}} - {{ $data->hocreator->employee_id ?? ''}}</strong><br>
        <strong>{{ $data->lastEditor->first_name ?? ''}} {{ $data->lastEditor->last_name ?? ''}} - {{ $data->hocreator->employee_id ?? ''}}</strong><br>
        
        <strong>{{ $nomineedata->pod_no ?? ''}}</strong>
        
        </div>
        <div class="col text-end">
        <label>Lead Number</label><br>
        <label>Creation Date</label><br>
        <label>Modified Date</label><br>
        
        </div>
        <div class="col">
        <strong>{{ $data->utrn}}</strong><br>
        <strong>{{ date('d M Y H:i:s',strtotime($data->created_at)) }}</strong><br>
        <strong>{{ date('d M Y H:i:s',strtotime($data->updated_at)) }}</strong><br>
        
        </div>
        </div>
        </div>
       
           </div>
        </div>     
        </div>

<div class="card mt-3">
        <div class="card-header label-font-header bg-card-header text-white">Policy Information</div>
        <div class="card-body bg-card-branch">
        <div class="row">
         <div class="row">
<div class="col-3 mb-3">
   <label class="form-label label-bold">Region</label>
   <select class="form-control form-control-design  form-select" name="region"  >
    <option value="">Select</option>
    @foreach($regions as $region)
                         <option {{(old('region',$data->region) == $region->name)?'selected':''}} value="{{$region->name}}">{{$region->name}}</option>
    @endforeach
   </select>
   @error('region')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Branch ID-Name</label>
   <input type="text" class="form-control form-control-design " name="branch" value="{{ $data->branch }}" >
   @error('branch')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Partner</label>
   <select class="form-control form-control-design  form-select" name="partner"  >
    <option value="">Select</option>
    @foreach($partners as $key=>$value)
      <option {{($data->partner == $value->partner)?'selected':''}} value="{{$value->partner}}">{{$value->partner}}</option>
    @endforeach
   </select>
   @error('partner')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Product</label>
   <select class="form-control form-control-design  form-select" name="product"  >
    <option value="">Select</option>
    @foreach($products as $key=>$value)
      <option {{($data->product== $value->product)?'selected':''}} value="{{$value->product}}">{{$value->product}}</option>
    @endforeach
   </select>
   @error('product')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Member Code</label>
   <input type="text" class="form-control form-control-design  numbersonly" name="mp_no" value="{{ $data->mp_no}}" placeholder="Enter Member Code">
   @error('mp_no')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Policy Number</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="policy_number" value="{{ $data->policy_number}}" placeholder="Enter Policy Number">
   @error('policy_number')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Policy Covered</label>
   <input type="date" class="form-control form-control-design  valid-date" name="policy_covered_date" value="{{ $data->policy_covered_date}}">
   @error('policy_covered_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Policy Expired Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="policy_expiry_date" value="{{ $data->policy_expiry_date}}">
   @error('policy_expiry_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Customer ID</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="cust_id" value="{{ $data->cust_id}}" placeholder="Enter Customer ID">
   @error('cust_id')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Actual ID</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="actual_id" value="{{ $data->actual_id}}" placeholder="Enter Actual ID">
   @error('actual_id')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Deceased Name</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="deceased_name" value="{{ $data->deceased_name}}" placeholder="Enter Deceased Name">
   @error('deceased_name')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Date of Birth</label>
   <input type="date" class="form-control form-control-design  valid-date" name="dob" value="{{ $data->dob}}">
   @error('dob')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Date of Death</label>
   <input type="date" class="form-control form-control-design  valid-date" name="date_of_death" value="{{ $data->date_of_death}}">
   @error('date_of_death')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Gender</label>
   <select class="form-control form-control-design  form-select" name="gender">
    <option value="">Select</option>
    <option {{ ( $data->gender=='Male')?'selected':''}}  value="Male">Male</option>
    <option {{ ( $data->gender=='Female')?'selected':''}}  value="Female">Female</option>
   </select>
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Age</label>
   <input type="text" class="form-control form-control-design  numbersonly" name="age" value="{{ $data->age}}" maxlength="3" placeholder="Enter Age">
   @error('age')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Deceased</label>
   <select class="form-control form-control-design  form-select" name="deceased" >
    <option value="">Select</option>
    @foreach($deceased as $key=>$value)
      <option {{ ( $data->deceased==$value)?'selected':''}} 
       value="{{$value}}">{{$value}}</option>
    @endforeach
   </select>
   @error('deceased')<div class="text-error">{{ $message }}</div>@enderror

</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Death Intimation Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="intimation_date" value="{{ $data->intimation_date}}">
   @error('intimation_date')<div class="text-error">{{ $message }}</div>@enderror
</div>


<div class="col-3 mb-3">
   <label class="form-label label-bold">Place of Death</label>
   <select class="form-control form-control-design  form-select" name="place_of_death">
    <option>Select</option>
    @foreach($placeofdeath as $key=>$value)
      <option {{ ( $data->place_of_death==$value->place)?'selected':''}}  value="{{$value->place}}">{{$value->place}}</option>
    @endforeach
   </select>
   @error('place_of_death')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Cause of Death</label>
   <select class="form-control form-control-design  form-select" name="cause_of_death" >
    <option>Select</option>
    @foreach($deathcause as $key=>$value)
      <option  {{ ( $data->cause_of_death==$value->cause)?'selected':''}}  value="{{$value->cause}}">{{$value->cause}}</option>
    @endforeach
   </select>
   @error('cause_of_death')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Loan Account ID</label>
   <input type="text" class="form-control form-control-design  numbersonly" name="load_acc_id" value="{{ $data->load_acc_id}}" placeholder="Enter Loan Account ID">
   @error('load_acc_id')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Loan Tenure</label>
   <input type="text" class="form-control form-control-design  numbersonly" name="loan_tenure" value="{{ $data->loan_tenure}}" maxlength="3" placeholder="Enter Loan Tenure">
   @error('loan_tenure')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Claim Amount</label>
   <input type="text" class="form-control form-control-design  number-input number-with-format" name="claim_amount" value="{{ $data->claim_amount}}" placeholder="Enter Claim Amount">
   @error('claim_amount')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Nominee Name</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="nominee_name" value="{{ $data->nominee_name}}" placeholder="Enter Nominee Name">
   @error('nominee_name')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Relationship</label>
   <select class="form-control form-control-design  form-select" name="relationship" >
    <option value="">Select</option>
    @foreach($relationship as $key=>$value)
      <option {{ ( $data->relationship==$value->relationship)?'selected':''}} value="{{$value->relationship}}">{{$value->relationship}}</option>
    @endforeach
   </select>
   @error('relationship')<div class="text-error">{{ $message }}</div>@enderror
</div>


           </div>
        </div>     
        </div>
       </div>

        <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header text-white">Claim Status</div>
        <div class="card-body bg-card-branch">
        <div class="row">
       
<div class="col-3 mb-3">
   <label class="form-label label-bold">Date of Document Received</label>
   <input type="date" class="form-control form-control-design  valid-date" name="doc_rec_date" value="{{ $data->doc_rec_date}}">
   @error('doc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
</div>


<div class="col-3 mb-3">
   <label class="form-label label-bold">Processed By</label>
   <select class="form-control form-control-design  form-select" name="processed_by">
    <option value="">Select</option>
    @foreach($procesedby as $proc)
     <option {{ ($data->processed_by==$proc)?'selected':'' }} value="{{$proc}}">{{$proc}}</option>
                   @endforeach
                   @error('processed_by')<div class="text-error">{{ $message }}</div>@enderror
   </select>
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Date of Submision to Partner</label>
   <input type="date" class="form-control form-control-design  valid-date" name="submit_to_partner_date" value="{{ $data->submit_to_partner_date}}">
   @error('submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
</div>


<div class="col-3 mb-3">
   <label class="form-label label-bold">Date of Re-submission to Partner</label>
   <input type="date" class="form-control form-control-design  valid-date" name="re_submit_to_partner_date" value="{{ $data->re_submit_to_partner_date}}">
   @error('re_submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

                    <div class="col-6 mb-3">
   <label class="form-label label-bold">HO Remarks</label>
   <textarea class="form-control form-control-design  clsAlphaNoOnly" name="ho_remark" placeholder="Remarks...">{{ $data->ho_remark}}</textarea>
   @error('ho_remark')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-6 mb-3">
   <label class="form-label label-bold">Remarks</label>
   <textarea type="text" class="form-control form-control-design  clsAlphaNoOnly" name="ho_remark2" placeholder="Remarks...">{{ $data->ho_remark2}}</textarea>
   @error('ho_remark2')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Claim Status</label>
   <select class="form-control form-control-design  form-select" name="cliam_status" >
    <option value="">Select</option>
    @foreach($claimstatus as $key=>$value)
      <option {{ ( $data->cliam_status==$value->claim_status)?'selected':''}} value="{{$value->claim_status}}">{{ (strlen($key+1)=='1'?'0':'' )}}{{$key+1}}-{{$value->claim_status}}</option>
    @endforeach
   </select>
   @error('cliam_status')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">CAS Status</label>
   <select class="form-control form-control-design  form-select" name="cas_status">
    <option value="">Select</option>
    <option {{ ( $data->cas_status=='CAS Process')?'selected':''}} value="CAS Process">CAS Process</option>
    
   </select>
   @error('cas_status')<div class="text-error">{{ $message }}</div>@enderror
</div> 

<div class="col-3 mb-3">
   <label class="form-label label-bold">SPDC/RL Status</label>
   <select class="form-control form-control-design  form-select" name="rl_status" >
    <option value="">Select</option>
    @foreach($rlStat as $key=>$stat)
     <option {{ ($data->rl_status==$stat->rl_status)?'selected':'' }} value="{{$stat->rl_status}}">{{$stat->rl_status}}</option>
                   @endforeach
                   @error('rl_status')<div class="text-error">{{ $message }}</div>@enderror
   </select>
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Notification Number</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="notification_number" value="{{ $data->notification_number}}" placeholder="Enter Notification Number">
   @error('notification_number')<div class="text-error">{{ $message }}</div>@enderror
</div>



           </div>
        </div>     
        </div>

        <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header text-white">Settlement Details</div>
        <div class="card-body bg-card-branch">
        <div class="row">

        <div class="col-3 mb-3">
   <label class="form-label label-bold">Loan Amount</label>
   <input type="text" class="form-control form-control-design  number-input number-with-format" name="loan_amount" value="{{ $data->loan_amount}}" placeholder="Enter Loan Amount">
   @error('loan_amount')<div class="text-error">{{ $message }}</div>@enderror
</div>

        <div class="col-3 mb-3">
   <label class="form-label label-bold">Loan Outstanding Amount</label>
   <input type="text" class="form-control form-control-design  number-input number-with-format" name="loan_outstanding" value="{{ $data->loan_outstanding}}" placeholder="Enter Loan Outstanding Amount">
   @error('loan_outstanding')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Recovered Amount</label>
   <input type="text" class="form-control form-control-design  numbersonly" name="recovered_amount" value="{{ $data->recovered_amount}}" placeholder="Enter Rcovered Amount">
   @error('recovered_amount')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Payable to Nominee</label>
   <input type="text" class="form-control form-control-design  number-input number-with-format" name="payable_to_nominee" value="{{ $data->payable_to_nominee}}" placeholder="Enter the Amount Payable to Nominee">
   @error('payable_to_nominee')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Date of Settlement</label>
   <input type="date" class="form-control form-control-design  valid-date" name="settlement_date" value="{{ $data->settlement_date}}">
   @error('settlement_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">NEFT Rejection Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="neft_rejection_date" value="{{ $data->neft_rejection_date}}">
   @error('neft_rejection_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">NEFT Reason for Rejection</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="neft_rejection_reason" value="{{ $data->neft_rejection_reason}}" placeholder="Enter Reason for NEFT Rejection">
   @error('neft_rejection_reason')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Final Settlement Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="final_settlement_date" value="{{ $data->final_settlement_date}}">
   @error('final_settlement_date')<div class="text-error">{{ $message }}</div>@enderror
</div>
<div class="col-3 mb-3">
   <label class="form-label label-bold">UTRN of MPH</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="utrn_mph" value="{{ $data->utrn_mph}}" placeholder="Enter UTRN of MPH">
   @error('utrn_mph')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">UTRN of Nominee</label>
   <input type="dtextte" class="form-control form-control-design  clsAlphaNoOnly" name="utrn_nominee" value="{{ $data->utrn_nominee}}" placeholder="Enter UTRN of Nominee">
   @error('utrn_nominee')<div class="text-error">{{ $message }}</div>@enderror
</div>

           </div>
        </div>     
        </div>

        <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header text-white">Recovery Details</div>
        <div class="card-body bg-card-branch">
        <div class="row">
        <div class="col-3 mb-3">
   <label class="form-label label-bold">Recovery Status</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="recovery_status" value="{{ $data->recovery_status}}" placeholder="Enter Recovery Status">
   @error('recovery_status')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Recoveries</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="recoveries" value="{{ old('recoveries',$data->recoveries)}}" placeholder="Enter Recoveries">
   @error('recoveries')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Bounced SPDC No</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="bounced_chq_no" value="{{ $data->bounced_chq_no}}" placeholder="Enter Bounced SPDC Number">
   @error('bounced_chq_no')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">SPDC Deposit Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="chq_deposit_date" value="{{ $data->chq_deposit_date}}" max="{{ date('Y-m-d')}}">
   @error('chq_deposit_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">SPDC Bounced Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="bounced_chq_date" value="{{ $data->bounced_chq_date}}">
   @error('bounced_chq_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">SPDC Bounced Reason</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="bounced_chq_reason" value="{{ $data->bounced_chq_reason}}" placeholder="Enter reason for SPDC Bounce">
   @error('bounced_chq_reason')<div class="text-error">{{ $message }}</div>@enderror
</div>

           </div>
        </div>     
        </div>

         <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header text-white">SPDC Details</div>
        <div class="card-body bg-card-branch">
        <div class="row">
       
<div class="col-3 mb-3">
   <label class="form-label label-bold">Acknowledgement Received Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="ack_rec_date" value="{{ $nomineedata->ack_rec_date}}">
   @error('ack_rec_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">SPDC Received Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="spdc_rec_date" value="{{ $nomineedata->spdc_rec_date}}">
   @error('spdc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Packet Number</label>
   <input type="text" class="form-control form-control-design numbersonly" name="pkt_no" value="{{ $nomineedata->pkt_no}}" placeholder="Enter Packet Number">
   @error('pkt_no')<div class="text-error">{{ $message }}</div>@enderror
</div>


           </div>
        </div>     
        </div>

        <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header text-white">Write-Off Details</div>
        <div class="card-body bg-card-branch">
        <div class="row">
        <div class="col-3 mb-3">
   <label class="form-label label-bold">Write off Received Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="write_off_rec" value="{{ $data->write_off_rec}}">
   @error('write_off_rec')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Write off Status</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="write_off_status" value="{{ $data->write_off_status}}" placeholder="Enter Write off status">
   @error('write_off_status')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Handed Over to Business Head</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="handed_to_bh" value="{{ $data->handed_to_bh}}" placeholder="Handed over to Business Head">
   @error('handed_to_bh')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label label-bold">Handed Over to Credit</label>
   <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="handed_to_credit" value="{{ $data->handed_to_credit}}" placeholder="Handed over to Credit">
   @error('handed_to_credit')<div class="text-error">{{ $message }}</div>@enderror
</div>
           </div>
        </div>     
        </div>

        <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header text-white">Additional Fields (Optional)</div>
        <div class="card-body">
        <div class="row">
        @foreach($additionalLeadfields as $key=>$val)
        <div class="col-3 mb-3">
   <label class="form-label label-bold">{{$val->settingData->field_name}}</label>
   <input class="form-control form-control-design" value="{{ $val->param_value}}">
   @error('write_off_rec')<div class="text-error">{{ $message }}</div>@enderror
</div>
@endforeach

           </div>
        </div>     
        </div>
        
        </fieldset>

        </form>
        
</div>
       
     <!-- BO -->

       
<div class="py-3 d-none" id="branch_off">
<form method="POST" action="{{route('save_nominee_details')}}">
@csrf
<fieldset disabled>
   <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header-branch text-white">Nominee Details</div>
        <div class="card-body bg-card-branch"> 
<div class="row">
<div class="col-3 mb-3">
   <label class="form-label">Nominee Name as per Bank Records</label>
   <input type="text" class="form-control form-control-design2 clsAlphaNoOnly" name="nominee_name_bank" value="{{$nomineedata->nominee_name_bank ?? ''}}" placeholder="Enter Nominee Name">
</div>

<div class="col-3 mb-3">
   <label class="form-label">Name of the Bank</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bank_name"  value="{{$nomineedata->bank_name ?? ''}}" placeholder="Enter Bank Name">
</div>

<div class="col-3 mb-3">
   <label class="form-label ">Bank Account Number</label>
   <input type="text" class="form-control form-control-design2  numbersonly" name="acc_number"  value="{{$nomineedata->acc_number ?? ''}}" placeholder="Enter acoount Number">
</div>

<div class="col-3 mb-3">
   <label class="form-label">IFSC Code</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="ifsc"  value="{{$nomineedata->ifsc ?? ''}}" placeholder="Enter IFSC">
</div>

<div class="col-3 mb-3">
   <label class="form-label">Bank Branch Name</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="branch_name"  value="{{$nomineedata->branch_name ?? ''}}" placeholder="Enter Branch Name">
</div>

<div class="col-3 mb-3">
   <label class="form-label">Nominee Contact No</label>
   <input type="text" class="form-control form-control-design2  numberonly" name="nominee_number" value="{{ $nomineedata->nominee_number}}" minlength="10" maxlength="10" placeholder="Enter Nominee Contact Number">
   @error('nominee_number')<div class="text-error">{{ $message }}</div>@enderror
</div>

<div class="col-3 mb-3">
   <label class="form-label">SPDC-Bank Name</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="spdc_bank_name"  value="{{$nomineedata->spdc_bank_name ?? ''}}" placeholder="Enter Bank Name">
</div>

<div class="col-3 mb-3">
   <label class="form-label">SPDC-Chq Number</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="spdc_chk_no"  value="{{$nomineedata->spdc_chk_no ?? ''}}" placeholder="Enter Chq Number">
</div>

<div class="col-3 mb-3">
   <label class="form-label">Courier Name</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="courier_name"  value="{{$nomineedata->courier_name ?? ''}}" placeholder="Enter Courier Name">
</div>

<div class="col-3 mb-3">
   <label class="form-label">POD Number</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="pod_no"  value="{{$nomineedata->pod_no ?? ''}}" placeholder="Enter POD Number">
</div>


<!-- <div class="col-3 mb-3">
   <label class="form-label label-bold">Cheque Sent Date</label>
   <input type="date" class="form-control form-control-design  valid-date" name="cheq_sent_date"  value="{{$nomineedata->cheq_sent_date ?? ''}}">
</div>
 -->
<div class="col-3 mb-3">
   <label class="form-label">Branch Remarks</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bo_remarks"  value="{{$nomineedata->bo_remarks ?? ''}}" placeholder="Remarks...">
</div>

<div class="col-3"></div>

<div class="col-6 mb-3">
   <label class="form-label">Maker at Branch</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bo_maker"  value="{{$nomineedata->bo_maker ?? ''}}" placeholder="Enter Maker EMP ID and Name">
</div>

<div class="col-6 mb-3">
   <label class="form-label">Checker at Branch</label>
   <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bo_checker"  value="{{$nomineedata->bo_checker ?? ''}}" placeholder="Enter Checker EMP ID and Name">
</div>

</div>
   </div>
   </div>
   </fieldset>
            <input type="hidden" name="lead_id" value="{{ encrypt($data->id) }}">

        </form>

</div>

       <input type="hidden" id="usertype" value="{{ Auth::user()->branch_id}}">
       <input type="hidden" id="landingTab" value="{{ $landingTab }}">

       <div class="py-3 d-none" id="checklist">
    
     <div class="card mt-3">
        <div class="card-header label-font-header bg-card-header-doc text-black">Documents</div>
        <div class="card-body bg-card-branch">
        <div class="row">
                   @foreach($documentdata as $key=>$val)
            <div class="col-md-2 mt-4 ">
                            <a class="" target="_blank" href="{{ URL::to('/')}}{{$val->filepath}}/{{$val->stored_name}}">
                                 <div class="card align-items-center cardcl2">
                                      <div class="card-body ">
                                           <img class="pdflogo" src="/insurance_images/pdf_icon.png">
                                      </div>
                                      <div class="form-label maxline2 p-1" title="{{ $val->original_name }}">{{ $val->original_name }}</div>
                                     
                                  </div>
                            </a>

                        </div>
           
           @endforeach
           </div>
        </div>      
       </div>

      
        </div>

</div>

<div class="floating-buttons">
   <button id="scrollTopBtn" title="Go to top">↑</button>
   <button id="scrollBottomBtn" title="Go to bottom">↓</button>
</div>
</div>



<!-- Modal -->
<div class="modal fade" id="nomineeModal" tabindex="-1" aria-labelledby="nomineeModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('verify_nominee_details') }}" enctype="multipart/form-data" id="nomineeForm">
        @csrf
        <div class="modal-header bg-success">
          <h5 class="modal-title label-bold text-white">Nominee Details Verification</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" class="form-control" name="modulename" id="recipient-name">

          <div class="form-group">
            <label>Checker Remarks</label>
            <textarea class="form-control" name="nominee_remarks" id="nominee_remarks" placeholder="Enter comments here"></textarea>
          </div>

        </div>
        <input type="hidden" name="nominee_id" value="{{ $nomineedata->id}}">

        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="action" id="btn_accept" value="Accepted">Accept</button>
          <button type="submit" class="btn btn-danger" name="action" value="Rework" id="btn_rework">Rework</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!--POD Modal -->
<div class="modal fade" id="podModal" tabindex="-1" aria-labelledby="podModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="{{ route('verify_pod_details') }}" enctype="multipart/form-data" id="podForm">
        @csrf
        <div class="modal-header bg-success">
          <h5 class="modal-title label-bold text-white">SPDC, POD Details Verification</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" class="form-control" name="modulename" id="recipient-name">

          <div class="form-group">
            <label>Checker Remarks</label>
            <textarea class="form-control" name="pod_remarks" id="pod_remarks" placeholder="Enter comments here"></textarea>
          </div>

        </div>
        <input type="hidden" name="nominee_id" value="{{ $nomineedata->id}}">

        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="action" value="Accepted">Accept</button>
          <button type="submit" class="btn btn-danger" name="action" value="Rework" id="btn_pod_rework">Rework</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>

document.querySelectorAll(".btn-toggle").forEach(button => {
        button.addEventListener("click", function () {

            showdata(this); // Call your function
        });
    });


   function showdata(button) {
// alert('ll');
   // Remove active class from all buttons
document.querySelectorAll('.btn-toggle').forEach(function(btn) {
   btn.classList.remove('active');
});

 // Add active class to the clicked button
button.classList.add('active');
var btn_val = button.value.trim();
//alert(btn_val)
     $('#landingTab').val(btn_val);
     

if(btn_val == 'ho'){
     $('#head_off').removeClass('d-none');
     $('#head_off').addClass('d-block');

     $('#branch_off').removeClass('d-block');
     $('#branch_off').addClass('d-none');

     $('#checklist').removeClass('d-block');
     $('#checklist').addClass('d-none');
     
   }

   if(btn_val == 'bo'){
     $('#head_off').removeClass('d-block');
     $('#head_off').addClass('d-none');

     $('#branch_off').removeClass('d-none');
     $('#branch_off').addClass('d-block');

     $('#checklist').removeClass('d-block');
     $('#checklist').addClass('d-none');
     
   }

   if(btn_val == 'cl'){
     $('#head_off').removeClass('d-blocl');
     $('#head_off').addClass('d-none');

     $('#branch_off').removeClass('d-block');
     $('#branch_off').addClass('d-none');

     $('#checklist').removeClass('d-none');
     $('#checklist').addClass('d-block');
     
   }
    
   }

   

   $(document).ready(function() {
      const userbranch = $('#usertype').val();
     
      if(userbranch == '1100'){
      $('#head_off').removeClass('d-none');
     $('#head_off').addClass('d-block');
          $('#ho').addClass('active');

     $('#branch_off').removeClass('d-block');
     $('#branch_off').addClass('d-none');

     $('#checklist').removeClass('d-block');
     $('#checklist').addClass('d-none');
     
      }
      else{
       $('#head_off').removeClass('d-block');
     $('#head_off').addClass('d-none');

     $('#branch_off').removeClass('d-none');
     $('#branch_off').addClass('d-block');
     $('#bo').addClass('active');

     $('#checklist').removeClass('d-block');
     $('#checklist').addClass('d-none');
          

      }
    });


 
    document.addEventListener("DOMContentLoaded", function () {
    const fileUrls = @json(array_map(fn($file) => asset($file), $formArray));
    const claimId = @json($data->id);
     
      
            /*fileUrls.forEach(url => {
                window.open(url, "_blank");
               
            });*/
            document.getElementById("openFilesBtn").addEventListener("click", function () {
       if (fileUrls.length === 0) return;

       // Open the first file
       const firstTab = window.open(fileUrls[0], "_blank");

       // From inside that tab (browser trusts it), open the rest
       setTimeout(() => {
           fileUrls.slice(1).forEach(url => {
               firstTab.window.open(url, "_blank");
           });
       }, 800);

            fetch("{{ route('audit.download.claim') }}", {
           method: "POST",
           headers: {
               "Content-Type": "application/json",
               "X-CSRF-TOKEN": "{{ csrf_token() }}"
           },
           body: JSON.stringify({ claim_id: claimId })
       })
       .then(response => response.json())
       .then(data => {
           console.log("Audit log saved:", data);
       })
       .catch(error => {
           console.error("Error saving audit log:", error);
       });

        });


        document.getElementById("btnChecklist").addEventListener("click", function () {
         fetch("{{ route('audit.download.checklist') }}", {
           method: "POST",
           headers: {
               "Content-Type": "application/json",
               "X-CSRF-TOKEN": "{{ csrf_token() }}"
           },
           body: JSON.stringify({ claim_id: claimId })
       })
       .then(response => response.json())
       .then(data => {
           console.log("Audit log saved:", data);
       })
       .catch(error => {
           console.error("Error saving audit log:", error);
       });

        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        const editBtn = document.getElementById('editBtn');
       
        editBtn.addEventListener('click', function () {
            const landingTab = document.getElementById('landingTab').value;
            const encryptedId = '{{ encrypt($data->id) }}'; // Server-side Laravel Blade

            // Construct the redirect URL
             const baseUrl = '{{ url("/") }}';

            // Redirect to absolute path
            window.location.href = `${baseUrl}/insurance/edit_claim_details/${landingTab}/${encryptedId}`;
        });
    });


$(document).on('click', '.confirm-link', function(e) {
    if (!confirm('You are cloning/duplicating the Lead details')) {
        e.preventDefault(); // stop navigation
    }
});

$(document).on('click', '.confirm-nominee_verification', function(e) {
    if (!confirm('You are Confirming that all Nominee bank details are correct . ')) {
        e.preventDefault(); // stop navigation
    }
});

$(document).on('click', '.confirm-spdc_verification', function(e) {
    if (!confirm('You are Confirming that SPDC and POD details are correct . ')) {
        e.preventDefault(); // stop navigation
    }
});

$(document).on('click', '.btn_nominee_remarks', function(e) {
const remarks = '{{ $nomineedata->nominee_checker_comments }}';
    Swal.fire({
        html: remarks,
        icon: 'info',
        confirmButtonText: 'OK'
    });
});

$(document).on('click', '.btn_pod_remarks', function(e) {
const remarks = '{{ $nomineedata->spdc_checker_comments }}';
    Swal.fire({
        html: remarks,
        icon: 'info',
        confirmButtonText: 'OK'
    });
})

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("nomineeForm");
    const remarks = document.getElementById("nominee_remarks");
    const reworkBtn = document.getElementById("btn_rework");

    reworkBtn.addEventListener("click", function (e) {
        if (remarks.value.trim() === "") {
            e.preventDefault(); // stop form submit
            $('#btn_accept').addClass('display','none');
            alert("Comments are mandatory for Rework!");
            remarks.focus();
        }
    });

    const podform = document.getElementById("podForm");
    const podremarks = document.getElementById("pod_remarks");
    const podreworkBtn = document.getElementById("btn_pod_rework");

    podreworkBtn.addEventListener("click", function (e) {
        if (podremarks.value.trim() === "") {
            e.preventDefault(); // stop form submit
            alert("Comments are mandatory for Rework!");
            podremarks.focus();
        }
    });

});

</script>
@endsection