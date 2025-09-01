@extends('layouts.insurance-app')
@section('content')

<div class="container">
	<div class="d-flex py-4">
		<label class="label-font-header">Update Lead Details - {{ $data->utrn }}</label>

        
		<div class="ms-auto">
			<a href="{{ route('view_claim_details',encrypt($data->id))}}" ><button class="btn btn-sm btn-info btn-text p-2" id="btn_view_details" >View Details</button> </a>

			<a href="{{ route('insurance_list')}}" ><button class="btn btn-sm btn-dark btn-text p-2" id="btn_view_list">View Lead List</button> </a>
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
				<button class="form-control btn-secondary btn btn-sm btn-toggle p-2 card-design border border-white" id="cl"  value="cl">Claim Documents </button>
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
        
        @if ($errors->any())

		     <script nonce='{{ env("CSP_NONCE") }}'>
		        document.addEventListener('DOMContentLoaded', function () {
		            let errorList = `<ul style="text-align:left;">@foreach ($errors->messages() as $key => $messages)
		                <li><strong>Error - {{$loop->iteration}} </strong> : {{ $messages[0] }}</li>
		            @endforeach</ul>`;

		            Swal.fire({
		                title: 'Validation Errors',
		                html: errorList,
		                icon: 'error',
		                confirmButtonText: 'OK'
		            });
		        });
		    </script>
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
	    <fieldset {{ (Auth::user()->branch_id == '1100')?'':'disabled'}}>

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
					    <label class="form-label label-bold">Region *</label>
					    <select class="form-control form-control-design  form-select" name="region"  >
					    	<option value="">Select</option>
					    	<!-- <option {{(old('region', $data->region )== 'South')?'selected':''}} value="South" >South</option>
					    	<option {{(old('region', $data->region) == 'North')?'selected':''}} value="North">North</option>
					    	<option {{(old('region', $data->region )== 'East')?'selected':''}} value="East">East</option>
					    	<option {{(old('region', $data->region) == 'West')?'selected':''}} value="West">West</option>	 -->
					    	@foreach($regions as $region)
	                          <option {{(old('region',$data->region) == $region->name)?'selected':''}} value="{{$region->name}}">{{$region->name}}</option>
					    	@endforeach
					    </select>
					    @error('region')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Branch ID-Name *</label>
					    <select class="form-control form-control-design form-select" name="branch" >
				    	<option value="">Select</option>
				    	@foreach($branch as $key=>$val)
                          <option {{ (old('branch',$data->branch )==($val->code.'-'.$val->name))? 'selected':'' }} value="{{ $val->code}}-{{ $val->name}}">{{ $val->code}}- {{ $val->name}}</option>
				    	@endforeach
				    </select>
					    @error('branch')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Partner *</label>
					    <select class="form-control form-control-design  form-select" name="partner" id="partner" >
					    	<option value="">Select</option>
					    	@foreach($partners as $key=>$value)
					    	   <option {{( (old('partner',$data->partner )== $value->partner))?'selected':''}} value="{{$value->partner}}">{{$value->partner}}</option>
					    	@endforeach
					    </select>
					    @error('partner')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Product *</label>
					    <select class="form-control form-control-design  form-select" name="product"  id="product">
					    	<option value="">Select</option>
					    	
					    </select>
					    @error('product')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Member Code</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="mp_no" value="{{ old('mp_no', $data->mp_no)}}" placeholder="Enter Member Code">
					    @error('mp_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Policy Number</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="policy_number" value="{{ old('policy_number', $data->policy_number)}}" placeholder="Enter Policy Number">
					    @error('policy_number')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Policy Covered *</label>
					    <input type="date" class="form-control form-control-design" name="policy_covered_date" value="{{ old('policy_covered_date', $data->policy_covered_date)}}">
					    @error('policy_covered_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Policy Expired Date</label>
					    <input type="date" class="form-control form-control-design" name="policy_expiry_date" value="{{ old('policy_expiry_date', $data->policy_expiry_date)}}">
					    @error('policy_expiry_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Customer ID</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="cust_id" value="{{ old('cust_id', $data->cust_id)}}" placeholder="Enter Customer ID">
					    @error('cust_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Actual ID *</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="actual_id" value="{{ old('actual_id', $data->actual_id )}}" placeholder="Enter Actual ID">
					    @error('actual_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Deceased Name *</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="deceased_name" value="{{ old('deceased_name',  $data->deceased_name )}}" placeholder="Enter Deceased Name">
					    @error('deceased_name')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Birth</label>
					    <input type="date" class="form-control form-control-design" name="dob" value="{{ old('dob', $data->dob)}}">
					    @error('dob')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Death *</label>
					    <input type="date" class="form-control form-control-design" name="date_of_death" value="{{ old('date_of_death', $data->date_of_death )}}" max="{{date('Y-m-d')}}">
					    @error('date_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Gender</label>
					    <select class="form-control form-control-design  form-select" name="gender">
					    	<option value="">Select</option>
					    	<option {{ ( (old('gender',$data->gender)=='Male'))?'selected':''}}  value="Male">Male</option>
					    	<option {{ ( (old('gender',$data->gender)=='Female'))?'selected':''}}  value="Female">Female</option>
					    </select>
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Age</label>
					    <input type="text" class="form-control form-control-design  numbersonly" name="age" value="{{ old('age', $data->age )}}" maxlength="3" placeholder="Enter Age">
					    @error('age')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Deceased</label>
					    <select class="form-control form-control-design  form-select" name="deceased" >
					    	<option value="">Select</option>
					    	@foreach($deceased as $key=>$value)
					    	   <option {{ ( (old('deceased',$data->deceased)==$value))?'selected':''}} 
					    	    value="{{$value}}">{{$value}}</option>
					    	@endforeach
					    </select>
					    @error('deceased')<div class="text-error">{{ $message }}</div>@enderror

					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Death Intimation Date</label>
					    <input type="date" class="form-control form-control-design" name="intimation_date" value="{{ old('intimation_date', $data->intimation_date )}}">
					    @error('intimation_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Place of Death</label>
					    <select class="form-control form-control-design  form-select" name="place_of_death">
					    	<option value="">Select</option>
					    	@foreach($placeofdeath as $key=>$value)
					    	   <option {{ ( (old('place_of_death',$data->place_of_death)==$value->place))?'selected':''}}  value="{{$value->place}}">{{$value->place}}</option>
					    	@endforeach
					    </select>
					    @error('place_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Cause of Death</label>
					    <select class="form-control form-control-design  form-select" name="cause_of_death" >
					    	<option value="">Select</option>
					    	@foreach($deathcause as $key=>$value)
					    	   <option  {{ ((old('cause_of_death', $data->cause_of_death)==$value->cause))?'selected':''}}  value="{{$value->cause}}">{{$value->cause}}</option>
					    	@endforeach
					    </select>
					    @error('cause_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Loan Account ID *</label>
					    <input type="text" class="form-control form-control-design clsAlphaNoOnly" name="load_acc_id" value="{{ old('load_acc_id', $data->load_acc_id )}}" placeholder="Enter Loan Account ID">
					    @error('load_acc_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Loan Tenure</label>
					    <input type="text" class="form-control form-control-design  numbersonly" name="loan_tenure" value="{{ old('loan_tenure', $data->loan_tenure)}}" maxlength="3" placeholder="Enter Loan Tenure">
					    @error('loan_tenure')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Claim Amount *</label>
					    <input type="text" class="form-control form-control-design  number-input number-with-format" name="claim_amount" value="{{ old('claim_amount', $data->claim_amount)}}" placeholder="Enter Claim Amount">
					    @error('claim_amount')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Nominee Name</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="nominee_name" value="{{ old('nominee_name', $data->nominee_name)}}" placeholder="Enter Nominee Name">
					    @error('nominee_name')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Relationship</label>
					    <select class="form-control form-control-design  form-select" name="relationship" >
					    	<option value="">Select</option>
					    	@foreach($relationship as $key=>$value)
					    	   <option {{ ( (old('relationship',$data->relationship) ==$value->relationship))?'selected':''}} value="{{$value->relationship}}">{{$value->relationship}}</option>
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
					    <input type="date" class="form-control form-control-design  " name="doc_rec_date" value="{{ old('doc_rec_date', $data->doc_rec_date)}}">
					    @error('doc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>


					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Processed By</label>
					    <select class="form-control form-control-design  form-select" name="processed_by">
					    	<option value="">Select</option>
					    	@foreach($procesedby as $proc)
					    	  <option {{ ( (old('processed_by',$data->processed_by)==$proc))?'selected':'' }} value="{{$proc}}">{{$proc}}</option>
		                    @endforeach
		                    @error('processed_by')<div class="text-error">{{ $message }}</div>@enderror
					    </select>
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Submision to Partner</label>
					    <input type="date" class="form-control form-control-design  " name="submit_to_partner_date" value="{{ old('submit_to_partner_date', $data->submit_to_partner_date)}}">
					    @error('submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Re-submission to Partner</label>
					    <input type="date" class="form-control form-control-design  " name="resubmission_to_partner_date" value="{{ old('resubmission_to_partner_date', $data->re_submit_to_partner_date)}}">
					    @error('resubmission_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					
                    <div class="col-6 mb-3">
					    <label class="form-label label-bold">HO Remarks</label>
					    <textarea class="form-control form-control-design  clsAlphaNoOnly" name="ho_remark" placeholder="Remarks...">{{ old('ho_remark', $data->ho_remark)}}</textarea>
					    @error('ho_remark')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					
					<div class="col-6 mb-3">
					    <label class="form-label label-bold">Remarks</label>
					    <textarea type="text" class="form-control form-control-design  clsAlphaNoOnly" name="ho_remark2" placeholder="Remarks...">{{ old('ho_remark2', $data->ho_remark2) }}</textarea>
					    @error('ho_remark2')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Claim Status</label>
					    <select class="form-control form-control-design  form-select" name="cliam_status" >
					    	<option value="">Select</option>
					    	@foreach($claimstatus as $key=>$value)
					    	   <option {{ ( (old('cliam_status',$data->cliam_status )==$value->claim_status))?'selected':''}} value="{{$value->claim_status}}">{{$value->claim_status}}</option>
					    	@endforeach
					    </select>
					    @error('cliam_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">CAS Status</label>
					    <select class="form-control form-control-design  form-select" name="cas_status">
					    	<option value="">Select</option>
					    	<option {{ ( (old('cas_status',$data->cas_status)=='CAS Process'))?'selected':''}} value="CAS Process">CAS Process</option>
					    	
					    </select>
					    @error('cas_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>	

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC/RL Status</label>
					    <select class="form-control form-control-design  form-select" name="rl_status" >
					    	<option value="">Select</option>
					    	@foreach($rlStat as $key=>$stat)
					    	  <option {{ ((old('rl_status',$data->rl_status)==$stat->rl_status))?'selected':'' }} value="{{$stat->rl_status}}">{{$stat->rl_status}}</option>
		                    @endforeach
		                    @error('rl_status')<div class="text-error">{{ $message }}</div>@enderror
					    </select>
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Notification Number</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="notification_number" value="{{ old('notification_number', $data->notification_number)}}" placeholder="Enter Notification Number">
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
					    <input type="text" class="form-control form-control-design  number-input number-with-format" name="loan_amount" value="{{ old('loan_amount', $data->loan_amount)}}" placeholder="Enter Loan Amount">
					    @error('loan_amount')<div class="text-error">{{ $message }}</div>@enderror
					</div>

        			<div class="col-3 mb-3">
					    <label class="form-label label-bold">Loan Outstanding Amount</label>
					    <input type="text" class="form-control form-control-design  number-input number-with-format" name="loan_outstanding" value="{{ old('loan_outstanding', $data->loan_outstanding)}}" placeholder="Enter Loan Outstanding Amount">
					    @error('loan_outstanding')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Recovered Amount</label>
					    <input type="text" class="form-control form-control-design  numbersonly" name="recovered_amount" value="{{ old('recovered_amount', $data->recovered_amount)}}" placeholder="Enter Rcovered Amount">
					    @error('recovered_amount')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Payable to Nominee</label>
					    <input type="text" class="form-control form-control-design  number-input number-with-format" name="payable_to_nominee" value="{{ old('payable_to_nominee', $data->payable_to_nominee)}}" placeholder="Enter the Amount Payable to Nominee">
					    @error('payable_to_nominee')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Settlement</label>
					    <input type="date" class="form-control form-control-design  " name="settlement_date" value="{{ old('settlement_date', $data->settlement_date)}}">
					    @error('settlement_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">NEFT Rejection Date</label>
					    <input type="date" class="form-control form-control-design  " name="neft_rejection_date" value="{{ old('neft_rejection_date', $data->neft_rejection_date)}}">
					    @error('neft_rejection_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">NEFT Reason for Rejection</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="neft_rejection_reason" value="{{ old('neft_rejection_reason', $data->neft_rejection_reason )}}" placeholder="Enter Reason for NEFT Rejection">
					    @error('neft_rejection_reason')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Final Settlement Date</label>
					    <input type="date" class="form-control form-control-design  " name="final_settlement_date" value="{{ old('final_settlement_date', $data->final_settlement_date)}}">
					    @error('final_settlement_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>
					
					<div class="col-3 mb-3">
					    <label class="form-label label-bold">UTRN of MPH</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="utrn_mph" value="{{ old('utrn_mph', $data->utrn_mph)}}" placeholder="Enter UTRN of MPH">
					    @error('utrn_mph')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">UTRN of Nominee</label>
					    <input type="dtextte" class="form-control form-control-design  clsAlphaNoOnly" name="utrn_nominee" value="{{ old('utrn_nominee', $data->utrn_nominee)}}" placeholder="Enter UTRN of Nominee">
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
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="recovery_status" value="{{ old('recovery_status', $data->recovery_status)}}" placeholder="Enter Recovery Status">
					    @error('recovery_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Recoveries</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="recoveries" value="{{ old('recoveries',$data->recoveries)}}" placeholder="Enter Recoveries">
					    @error('recoveries')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Bounced SPDC No</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="bounced_chq_no" value="{{ old('bounced_chq_no', $data->bounced_chq_no)}}" placeholder="Enter Bounced SPDC Number">
					    @error('bounced_chq_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Deposit Date</label>
					    <input type="date" class="form-control form-control-design  " name="chq_deposit_date" value="{{ old('chq_deposit_date', $data->chq_deposit_date)}}" max="{{ date('Y-m-d')}}">
					    @error('chq_deposit_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Bounced Date</label>
					    <input type="date" class="form-control form-control-design  " name="bounced_chq_date" value="{{ old('bounced_chq_date', $data->bounced_chq_date)}}">
					    @error('bounced_chq_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Bounced Reason</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="bounced_chq_reason" value="{{ old('bounced_chq_reason', $data->bounced_chq_reason)}}" placeholder="Enter reason for SPDC Bounce">
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
					    <input type="date" class="form-control form-control-design  " name="ack_rec_date" value="{{ old('ack_rec_date', $nomineedata->ack_rec_date)}}">
					    @error('ack_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Received Date</label>
					    <input type="date" class="form-control form-control-design  " name="spdc_rec_date" value="{{ old('spdc_rec_date', $nomineedata->spdc_rec_date )}}">
					    @error('spdc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Packet Number</label>
					    <input type="text" class="form-control form-control-design numbersonly" name="pkt_no" value="{{ old('pkt_no', $nomineedata->pkt_no )}}" placeholder="Enter Packet Number">
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
					    <input type="date" class="form-control form-control-design  " name="write_off_rec" value="{{ old('write_off_rec', $data->write_off_rec)}}">
					    @error('write_off_rec')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Write off Status</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="write_off_status" value="{{ old('write_off_status', $data->write_off_status)}}" placeholder="Enter Write off status">
					    @error('write_off_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Handed Over to Business Head</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="handed_to_bh" value="{{ old('handed_to_bh', $data->handed_to_bh)}}" placeholder="Handed over to Business Head">
					    @error('handed_to_bh')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Handed Over to Credit</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="handed_to_credit" value="{{ old('handed_to_credit', $data->handed_to_credit)}}" placeholder="Handed over to Credit">
					    @error('handed_to_credit')<div class="text-error">{{ $message }}</div>@enderror
					</div>
        	    </div>
        	</div>    		
        </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header bg-card-header text-white">Additional Fields (Optional)</div>
        	<div class="card-body">
        		<div class="row">
        			@foreach($allSettings as $setting)
				    @php
				        $field = $existingFields->get($setting->id); // child if exists
				    @endphp
				    <div class="col-3 mb-3">
				        <label class="form-label label-bold">{{ $setting->field_name }}</label>
				        <input type="text"
				               class="form-control form-control-design"
				               name="af_{{ $setting->id }}"
				               value="{{ old('af_'.$setting->id, $field ? $field->param_value : '') }}">
				    </div>
				    @endforeach


					
        	    </div>
        	</div>    		
        </div>
        
        </fieldset>

        @if(auth::user()->branch_id == '1100')
		<div class="d-flex mt-3">
			<div class="ms-auto">
				<button type="submit" class="btn btn-sm btn-success btn-text p-2" id="btn_update_ho">Update</button>
			</div>
	    </div>
	    @endif

        </form>
        
		</div>
       
     <!-- BO -->

       
		<div class="py-3 d-none" id="branch_off">
			<form method="POST" action="{{route('save_nominee_details')}}" enctype="multipart/form-data" >
			@csrf
			
		    <div class="card mt-3">
        	<div class="card-header label-font-header bg-card-header-branch text-white">Nominee Details</div>
        	<div class="card-body bg-card-branch">		
			<div class="row">
				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Nominee Name as per Bank Records</label>
				    <input type="text" class="form-control form-control-design2 clsAlphaNoOnly" name="nominee_name_bank" value="{{ old('nominee_name_bank',$nomineedata->nominee_name_bank) ?? ''}}" placeholder="Enter Nominee Name">
				    @error('nominee_name_bank')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Name of the Bank</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bank_name"  value="{{ old('bank_name',$nomineedata->bank_name ) ?? ''}}" placeholder="Enter Bank Name">
				    @error('bank_name')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Bank Account Number</label>
				    <input type="text" class="form-control form-control-design2  numbersonly" name="acc_number"  value="{{ old('acc_number',$nomineedata->acc_number ) ?? ''}}" placeholder="Enter acoount Number">
				    @error('acc_number')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">IFSC Code</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="ifsc" id="ifscInput" value="{{ old('ifsc',$nomineedata->ifsc ) ?? ''}}" placeholder="Enter IFSC" minlength="11" maxlength="11">
				    @error('ifsc')<div class="text-error">{{ $message }}</div>@enderror
				    <div id="ifscError" class="text-error d-none">IFSC code must be exactly 11 characters.</div>
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Bank Branch Name</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="branch_name"  value="{{ old('branch_name',$nomineedata->branch_name ) ?? ''}}" placeholder="Enter Branch Name">
				    @error('branch_name')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Nominee Contact No</label>
				    <input type="text" class="form-control form-control-design2  numbersonly" name="nominee_number" value="{{ old('nominee_number',$nomineedata->nominee_number) ?? '' }}" id="mobile_input" minlength="10" maxlength="10" placeholder="Enter Nominee Contact Number">
				    @error('nominee_number')<div class="text-error">{{ $message }}</div>@enderror
				    <div id="mobileError" class="text-error d-none">Contact Number must be exactly 10 Digits.</div>
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">SPDC-Bank Name</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="spdc_bank_name"  value="{{ old('spdc_bank_name',$nomineedata->spdc_bank_name )?? ''}}" placeholder="Enter Bank Name">
				    @error('spdc_bank_name')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">SPDC-Chq Number</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="spdc_chk_no"  value="{{ old('spdc_chk_no',$nomineedata->spdc_chk_no )?? ''}}" placeholder="Enter Chq Number">
				    @error('spdc_chk_no')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Courier Name</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="courier_name"  value="{{ old('courier_name',$nomineedata->courier_name )?? ''}}" placeholder="Enter Courier Name">
				    @error('courier_name')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">POD Number</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="pod_no"  value="{{ old('pod_no',$nomineedata->pod_no) ?? ''}}" placeholder="Enter POD Number">
				    @error('pod_no')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Branch Remarks</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bo_remarks"  value="{{ old('bo_remarks',$nomineedata->bo_remarks) ?? ''}}" placeholder="Remarks...">
				    @error('bo_remarks')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3"></div>

				<!-- <div class="col-6 mb-3">
				    <label class="form-label label-bold">Maker at Branch</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bo_maker"  value="{{ old('bo_maker',$nomineedata->bo_maker )?? ''}}" placeholder="Enter Maker EMP ID and Name">
				    @error('bo_maker')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-6 mb-3">
				    <label class="form-label label-bold">Checker at Branch</label>
				    <input type="text" class="form-control form-control-design2  clsAlphaNoOnly" name="bo_checker"  value="{{ old('bo_checker',$nomineedata->bo_checker) ?? ''}}" placeholder="Enter Checker EMP ID and Name">
				    @error('bo_checker')<div class="text-error">{{ $message }}</div>@enderror
				</div> -->

			  </div>
		     </div>
		   </div>
            <input type="hidden" name="lead_id" value="{{ encrypt($data->id) }}">

            
		
			<div class="d-flex py-4">
				<div class="ms-auto">
					<button type="submit" class="btn btn-sm btn-success btn-text p-2">Update</button>
				</div>
		    </div>
		    
        </form>


		</div>

		
      <!-- BO -->

      <!-- Documents -->
      <div class="py-3 d-none" id="checklist">
      <form action=" {{ route('update_documents')}} " id="pdfForm" >
      	@csrf
      <div class="card mt-3">
        	<div class="card-header label-font-header bg-card-header-doc text-black">Documents</div>
        	<div class="card-body bg-card-branch">
        		<div class="row">
        			<label class="form-label label-bold text-black">Upload (Please name the documents properly before upload )</label>
        			<div class="col-4 mb-3">
				    
				    <input type="file" class="form-control form-control-design3 clsAlphaNoOnly"  id="pdfInput" name="files" multiple accept="application/pdf">
				    
				</div>

                     <div class="preview-container" id="previewContainer"></div>
	        		<div class="row mt-4">
	                  
                       <label class="label-bold text-black">Saved Documents</label>
		        	   @foreach($documentdata as $doc)
					    <div class="preview-box" id="doc-{{ $doc->id }}">
					        <div class="card align-items-center cardcl2">
                                      <div class="card-body">
                                           <a target="_blank" href="{{ URL::to('/')}}{{$doc->filepath}}/{{$doc->stored_name}}"><img class="pdflogo" src="/insurance_images/pdf_icon.png"></a>
                                      </div>
                                      <div class="form-label maxline2 maxwidth p-1" title="{{ $doc->original_name }}">{{ $doc->original_name }}</div>
                                     <button type="button" class="remove-existing-btn mb-2" data-id="{{ $doc->id }}">Remove</button>
                                  </div>
					        
					    </div>
					@endforeach
					<input type="hidden" name="delete_doc_ids[]" id="delete_doc_ids">


	        	    </div>
	        	

        	   
        	</div>    		
        </div>
        </div>
         <input type="hidden" name="lead_id" value="{{ encrypt($data->id) }}">
			<div class="d-flex py-4">
				<div class="ms-auto">
					<button type="submit" class="btn btn-sm btn-success btn-text p-2" id="btn_update_bo">Update</button>
				</div>
		    </div>

        </form>
        </div>

       
       <input type="hidden" id="usertype" value="{{ Auth::user()->branch_id}}">
	</div>

	 <div class="floating-buttons">
	    <button id="scrollTopBtn" title="Go to top">↑</button>
	    <button id="scrollBottomBtn" title="Go to bottom">↓</button>
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



 let selectedFiles = [];

    const input = document.getElementById('pdfInput');
    const previewContainer = document.getElementById('previewContainer');

    input.addEventListener('change', function (e) {
        const newFiles = Array.from(e.target.files);

        newFiles.forEach(file => {
            if (file.type === 'application/pdf') {
                selectedFiles.push(file);
                showPreview(file);
            }

        });

        input.value = ''; // allow same file again
    });

    function showPreview(file) {
        const reader = new FileReader();

        reader.onload = function (e) {
            const box = document.createElement('div');
            box.classList.add('preview-box');
            box.classList.add('border');
            box.classList.add('cardcl2');

            // Show file name
            const fileName = document.createElement('span');
            fileName.classList.add('file-name');
            fileName.classList.add('maxline2');
            fileName.classList.add('form-label');
            fileName.classList.add('mt-2');
            fileName.textContent = file.name;

            // Show PDF preview
            const icon = document.createElement('img');
			icon.src = '/insurance_images/pdf_icon.png'; // <- load from Laravel public/images
			icon.alt = 'PDF';
			icon.classList.add('pdf-icon');
			icon.classList.add('pdflogo');

            // Remove button
            const removeBtn = document.createElement('button');
            removeBtn.textContent = 'X';
            removeBtn.classList.add('remove-btn');
            

            removeBtn.onclick = function () {
                selectedFiles = selectedFiles.filter(f => f !== file);
                box.remove();
            };

            box.appendChild(removeBtn);
            
            box.appendChild(icon);
            box.appendChild(fileName);
            previewContainer.appendChild(box);
        };

        reader.readAsDataURL(file);
    }

    document.getElementById('pdfForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData();

        // Add PDF files
        selectedFiles.forEach(file => {
            formData.append('pdfs[]', file);
        });

        // Add all other inputs in the form
        form.querySelectorAll('input, textarea, select').forEach(input => {
            if (input.type !== 'file') {
                formData.append(input.name, input.value);
            }
        });

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
           
            Swal.fire({
                title: 'Message',
                text: data.message ,
                icon: (data.status == 'false')? 'error':'success',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                console.log('result:', result);
                if (result.isConfirmed) {
                    console.log('Redirecting...');
                    location.reload();
                }
            });

            previewContainer.innerHTML = '';
            selectedFiles = [];
            form.reset();
        })
        .catch(err => {
            console.error(err);
            alert('Upload failed.: Please check the file');
        });
    });

  let deleteDocIds = [];

document.querySelectorAll('.remove-existing-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const docId = this.getAttribute('data-id');

        if (!deleteDocIds.includes(docId)) {
            deleteDocIds.push(docId);
            document.getElementById('delete_doc_ids').value = JSON.stringify(deleteDocIds);

            // Grey out the doc preview
            const previewBox = document.getElementById('doc-' + docId);
            previewBox.classList.add('marked-for-delete');
        }
    });
});

$(document).ready(function() {
  const opentab = '{{ $spec}}';
  
  if(opentab == 'ho'){
      $('#head_off').removeClass('d-none');
      $('#head_off').addClass('d-block');
      $('#ho').addClass('active');

      $('#branch_off').removeClass('d-block');
      $('#branch_off').addClass('d-none');

      $('#checklist').removeClass('d-block');
      $('#checklist').addClass('d-none');
 
    }

    if(opentab == 'bo'){
      $('#head_off').removeClass('d-block');
      $('#head_off').addClass('d-none');

      $('#branch_off').removeClass('d-none');
      $('#branch_off').addClass('d-block');
      $('#bo').addClass('active');

      $('#checklist').removeClass('d-block');
      $('#checklist').addClass('d-none');
 
    }

    if(opentab == 'cl'){
      $('#head_off').removeClass('d-block');
      $('#head_off').addClass('d-none');

      $('#branch_off').removeClass('d-block');
      $('#branch_off').addClass('d-none');
      
      $('#checklist').removeClass('d-none');
      $('#checklist').addClass('d-block');
      $('#cl').addClass('active');

 
    }

});

 const coveredInput = document.querySelector('input[name="policy_covered_date"]');
    const tenureInput = document.querySelector('input[name="loan_tenure"]');
    const expiryInput = document.querySelector('input[name="policy_expiry_date"]');
    

    function calculateExpiryDate() {
       const coveredDate = new Date(coveredInput.value);
        const tenureMonths = parseInt(tenureInput.value);

        if (!isNaN(coveredDate.getTime()) && !isNaN(tenureMonths)) {
            const expiryDate = new Date(coveredDate);
            expiryDate.setMonth(expiryDate.getMonth() + tenureMonths);

            expiryDate.setDate(expiryDate.getDate() - 1);

            const yyyy = expiryDate.getFullYear();
            const mm = String(expiryDate.getMonth() + 1).padStart(2, '0');
            const dd = String(expiryDate.getDate()).padStart(2, '0');

            expiryInput.value = `${yyyy}-${mm}-${dd}`;
            expiryInput.min = coveredInput.value;
        }
    }

    coveredInput.addEventListener('change', calculateExpiryDate);
    tenureInput.addEventListener('input', calculateExpiryDate);

    calculateExpiryDate(); // initialize if values are prefilled

    const dobInput = document.querySelector('input[name="dob"]');
    const ageInput = document.querySelector('input[name="age"]');

    dobInput.addEventListener('change', function () {
        const dob = new Date(dobInput.value);
        const today = new Date();
       
        if (!isNaN(dob.getTime())) {
            let years = today.getFullYear() - dob.getFullYear();
            let months = today.getMonth() - dob.getMonth();
            let days = today.getDate() - dob.getDate();

            if (days < 0) {
                months--; // not completed this month
            }

            if (months < 0) {
                years--;
                months += 12;
            }

            ageInput.value = `${years}`;
        } else {
            ageInput.value = '';
        }
    });
    
    const intimationReceivedDateInput = document.querySelector('input[name="intimation_date"]');
    const documentReceivedDateInput = document.querySelector('input[name="doc_rec_date"]');
    const documentsubmissionDateInput = document.querySelector('input[name="submit_to_partner_date"]');
    const documentre_submissionDateInput = document.querySelector('input[name="re_submit_to_partner_date"]');
    const dodInput = document.querySelector('input[name="date_of_death"]');

    function RestrictDocReceivedDate() {
      // documentReceivedDateInput.min = intimationReceivedDateInput.value;
    }
    intimationReceivedDateInput.addEventListener('change', RestrictDocReceivedDate);

     function RestrictresubmissiondDate() {
        documentre_submissionDateInput.min = documentsubmissionDateInput.value;
    }
    
    documentsubmissionDateInput.addEventListener('change', RestrictresubmissiondDate);

    function checkfordeathdate() {
       if (dodInput && coveredInput) {
        let dodDate = new Date(dodInput.value);
        let coveredDate = new Date(coveredInput.value);
           
	        if (dodDate < coveredDate) {
	            Swal.fire({
	                icon: 'info',
	                title: 'Invalid Date',
	                text: 'Date of Death is prior to date of policy covered date',
	                confirmButtonText: 'OK'
	            });

	        }
       }  
    }
    dodInput.addEventListener('change', checkfordeathdate);

     $(document).ready(function() {
      let partnerId = '{{$data->partner }}';
	    let $productSelect = $('#product').html('<option value="">Select</option>');
        var selectedProductId = '{{$data->product }}';
       

	    if (partnerId) {
	        $.get('{{ route("get_products") }}', { partner_id: partnerId }, function (data) {
	            $.each(data, function (id, product) {
	            	let selected = (product == selectedProductId) ? 'selected' : '';
	                $productSelect.append(`<option ${selected} value="${product}">${product}</option>`);
	            });
	        });
	    }
     
   	});
    
    
    $(document).on('change', '#partner', function () {
	    console.log('Partner changed:', $(this).val()); // debug
	    let partnerId = $(this).val();
	    let $productSelect = $('#product').html('<option value="">Select</option>');
        
	    if (partnerId) {
	        $.get('{{ route("get_products") }}', { partner_id: partnerId }, function (data) {
	            $.each(data, function (id, product) {
	            	  $productSelect.append(`<option value="${product}">${product}</option>`);
	            });
	        });
	    }
	});


$(document).ready(function(){
    $('#ifscInput').on('input', function() {
        let val = $(this).val();

        if(val.length < 11) {
            $('#ifscError').removeClass('d-none').text("Minimum 11 characters required.");
        } 
        else if(val.length > 11) {
            $('#ifscError').removeClass('d-none').text("Maximum 11 characters allowed.");
            $(this).val(val.substring(0, 11)); // trim extra chars
        } 
        else {
            $('#ifscError').addClass('d-none'); // hide error if valid
        }
    });

    $('#mobile_Input').on('input', function() {
        let val = $(this).val();

        if(val.length < 11) {
            $('#mobileError').removeClass('d-none').text("Minimum 10 characters required.");
        } 
        else if(val.length > 11) {
            $('#mobileError').removeClass('d-none').text("Maximum 10 characters allowed.");
            $(this).val(val.substring(0, 11)); // trim extra chars
        } 
        else {
            $('#mobileError').addClass('d-none'); // hide error if valid
        }
    });
});




</script>


@endsection