@extends('layouts.insurance-app')
@section('content')

<div class="container">
	<div class=" d-flex">
		<label class="label-font-header">Create New Lead</label>
		<a class="ms-auto nav-link" href="{{ route('insurance_list')}}">
		  <button class="btn btn-sm btn-secondary btn-text p-2 me-4">Back to List</button>
	   </a>
	</div>

	<div class="py-2">
		<div class="row">
			<!-- <div class="col-3">
				<button class="form-control form-control-design  btn btn-sm btn-secondary btn-toggle p-2 active card-design"  value="ho">Head Office </button>
			</div> -->

			<!-- <div class="col-3">
				<button class="form-control form-control-design  btn-secondary btn btn-sm btn-toggle p-2 card-design"  value="bo">Branch Office </button>
			</div> -->

			<!-- <div class="col-3">
				<button class="form-control form-control-design  btn-secondary btn btn-sm btn-toggle p-2 card-design"  value="cl">Check List </button>
			</div> -->
		</div> 

		@if ($errors->any())

		     <script nonce='{{ env("CSP_NONCE") }}'>
		        document.addEventListener('DOMContentLoaded', function () {
		            let errorList = `<ul style="text-align:left;">@foreach ($errors->messages() as $field => $messages)
		                <li><strong>Error - {{$loop->iteration}} </strong>: {{ $messages[0] }}</li>
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
		                if (result.isConfirmed) {
		                    console.log('Redirecting...');
		                    window.location.href = "{{ url('/insurance/claim_forms') }}";
		                }
		            });
		        }, 300); // Delay to ensure full render
		    });
		</script>
		@php
		    session()->forget('success');
		    session()->forget('errors');
		@endphp
		@endif
		
		@if(Session::has('failure'))
		 <script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
		  var mesage = '{{ session('failure') }}';
		  Swal.fire({
		        title: 'Message',
		        text: mesage,
		        icon: 'success',  
		        confirmButtonText: 'OK'
		    });
		 </script>
		 
		@endif 

		<!-- @if ($errors->any())
		    <div class="alert alert-danger">
		        <strong>Errors:</strong>
		        <ul>
		            @foreach ($errors->all() as $err)
		                <li>{{ $err }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif -->
        
		<div class="py-3 d-block" id="head_off">
	    <form id="myForm" method="POST" action="{{route('save_claim_details')}}" >
	    @csrf	
        
        <div class="card">
        	<div class="card-header label-font-header bg-card-header text-white">Policy Information</div>
        	<div class="card-body ">
    		 <div class="row">
	    		 <div class="col-3 mb-3">
				    <label class="form-label label-bold">Region</label>
				    <select class="form-control form-control-design  form-select" name="region"  >
				    	<option value="">Select</option>
				    	<option {{(old('region') == 'South')?'selected':''}} value="South" >South</option>
				    	<option {{(old('region') == 'North')?'selected':''}} value="North">North</option>
				    	<option {{(old('region') == 'East')?'selected':''}} value="East">East</option>
				    	<option {{(old('region') == 'West')?'selected':''}} value="West">West</option>	
				    </select>
				    @error('region')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Branch ID-Name</label>
				   
				    <select class="form-control form-control-design" name="branch" id="branch">
				    	<option value="">Select</option>
				    	@foreach($branch as $key=>$val)
                          <option  {{ (old('branch') == ($val->code.'-'.$val->name) )?'selected':''}} value="{{ $val->code}}-{{ $val->name}}">{{ $val->code}}-{{ $val->name}}</option>
				    	@endforeach
				    </select>
				    @error('branch')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Partner</label>
				    <select class="form-control form-control-design form-select" name="partner" id="partner"  >
				    	<option value="">Select</option>
				    	@foreach($partners as $key=>$value)
				    	   <option {{(old('partner') == $value->partner)?'selected':''}} value="{{$value->partner}}">{{$value->partner}}</option>
				    	@endforeach
				    </select>
				    @error('partner')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Product</label>
				    <select class="form-control form-control-design  form-select" name="product" id="product" >
				    	<option value="">Select</option>
				    	@foreach($products as $key=>$value)
				    	   <option {{(old('product') == $value->product)?'selected':''}} value="{{$value->product}}">{{$value->product}}</option>
				    	@endforeach
				    </select>
				    @error('product')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Member Code</label>
				    <input type="text" class="form-control form-control-design clsAlphaNoOnly" name="mp_no" value="{{ old('mp_no')}}" placeholder="Enter Member Code">
				    @error('mp_no')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Policy Number</label>
				    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="policy_number" value="{{ old('policy_number')}}" placeholder="Enter Policy Number" >
				    @error('policy_number')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Policy Covered Date</label>
				    <input type="date" class="form-control form-control-design" name="policy_covered_date" value="{{ old('policy_covered_date')}}">
				    @error('policy_covered_date')<div class="text-error">{{ $message }}</div>@enderror
				    <div class="text-error text-danger small" id="covered-error"></div>
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Policy Expired Date</label>
				    <input type="date" class="form-control form-control-design" name="policy_expiry_date" value="{{ old('policy_expiry_date')}}" >
				    @error('policy_expiry_date')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Customer ID</label>
				    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="cust_id" value="{{ old('cust_id')}}" placeholder="Enter Customer ID">
				    @error('cust_id')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Actual ID</label>
				    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="actual_id" value="{{ old('actual_id')}}" placeholder="Enter Actual ID">
				    @error('actual_id')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Deceased Name</label>
				    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="deceased_name" value="{{ old('deceased_name')}}" placeholder="Enter Deceased Name">
				    @error('deceased_name')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Date of Birth</label>
				    <input type="date" class="form-control form-control-design" name="dob" max="{{ date('Y-m-d')}}" value="{{ old('dob')}}">
				    @error('dob')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Date of Death</label>
				    <input type="date" class="form-control form-control-design" name="date_of_death" value="{{ old('date_of_death')}}" max="{{ date('Y-m-d')}}">
				    @error('date_of_death')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Gender</label>
				    <select class="form-control form-control-design  form-select" name="gender">
				    	<option value="">Select</option>
				    	<option {{ ( old('gender')=='Male')?'selected':''}}  value="Male">Male</option>
				    	<option {{ ( old('gender')=='Female')?'selected':''}}  value="Female">Female</option>
				    </select>
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Age</label>
				    <input type="text" class="form-control form-control-design  numbersonly" name="age" value="{{ old('age')}}" placeholder="Enter Age">
				    @error('age')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Deceased</label>
				    <select class="form-control form-control-design  form-select clsAlphaNoOnly" name="deceased" >
				    	<option value="">Select</option>
				    	@foreach($deceased as $key=>$value)
				    	   <option {{ ( old('deceased')==$value)?'selected':''}} 
				    	    value="{{$value}}">{{$value}}</option>
				    	@endforeach
				    </select>
				    @error('deceased')<div class="text-error">{{ $message }}</div>@enderror

				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Date Of Death Intimation</label>
				    <input type="date" class="form-control form-control-design" name="intimation_date" value="{{ old('intimation_date')}}" max="{{ date('Y-m-d')}}">
				    @error('intimation_date')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Place of Death</label>
				    <select class="form-control form-control-design  form-select" name="place_of_death">
				    	<option>Select</option>
				    	@foreach($placeofdeath as $key=>$value)
				    	   <option {{ ( old('place_of_death')==$value->id)?'selected':''}}  value="{{$value->id}}">{{$value->place}}</option>
				    	@endforeach
				    </select>
				    @error('place_of_death')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Cause of Death</label>
				    <select class="form-control form-control-design  form-select " name="cause_of_death">
				    	<option>Select</option>
				    	@foreach($deathcause as $key=>$value)
				    	   <option {{ ( old('cause_of_death')==$value->cause)?'selected':''}} value="{{$value->cause}}">{{$value->cause}}</option>
				    	@endforeach
				    </select>
				    @error('cause_of_death')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				
				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Loan Account ID</label>
				    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="load_acc_id" value="{{ old('load_acc_id')}}" placeholder="Enter Loan Account ID">
				    @error('load_acc_id')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Loan Tenure</label>
				    <input type="text" class="form-control form-control-design numbersonly" name="loan_tenure" value="{{ old('loan_tenure')}}" maxlength="3" placeholder="Enter Loan Tenure">
				    @error('loan_tenure')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Claim Amount</label>
				    <input type="text" class="form-control form-control-design  number-with-format" name="claim_amount" value="{{ old('claim_amount')}}" placeholder="Enter Claim Amount">
				    @error('claim_amount')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Nominee Name</label>
				    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="nominee_name" value="{{ old('nominee_name')}}" placeholder="Enter Nominee Name">
				    @error('nominee_name')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label label-bold">Relationship</label>
				    <select class="form-control form-control-design  form-select" name="relationship" >
				    	<option value="">Select</option>
				    	@foreach($relationship as $key=>$value)
				    	   <option {{ ( old('relationship')==$value->relationship)?'selected':''}} value="{{$value->relationship}}">{{$value->relationship}}</option>
				    	@endforeach
				    </select>
				    @error('relationship')<div class="text-error">{{ $message }}</div>@enderror
				</div>

				
    		 </div>
        	</div>
        </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header bg-card-header text-white">Claim Status</div>
        	<div class="card-body">
        		<div class="row">
        		   

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Document Received</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="doc_rec_date" value="{{ old('doc_rec_date')}}" max="{{ date('Y-m-d')}}">
					    @error('doc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Processed By</label>
					    <select class="form-control form-control-design  form-select" name="processed_by">
					    	<option value="">Select</option>
					    	@foreach($procesedby as $proc)
					    	  <option {{ (old('processed_by')==$proc)?'selected':'' }} value="{{$proc}}">{{$proc}}</option>
		                    @endforeach
		                    @error('processed_by')<div class="text-error">{{ $message }}</div>@enderror
					    </select>
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Submision to Partner</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="submit_to_partner_date" value="{{ old('submit_to_partner_date')}}" max="{{ date('Y-m-d')}}">
					    @error('submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Re-submision to Partner</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="re_submit_to_partner_date" value="{{ old('re_submit_to_partner_date')}}" max="{{ date('Y-m-d')}}">
					    @error('re_submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>	

					 <div class="col-6 mb-3">
					    <label class="form-label label-bold">HO Remarks</label>
					    <textarea class="form-control form-control-design  clsAlphaNoOnly" name="ho_remark" placeholder="Remarks...">{{ old('ho_remarks')}}</textarea>
					    @error('ho_remark')<div class="text-error">{{ $message }}</div>@enderror
					</div>
					
					<div class="col-6 mb-3">
					    <label class="form-label label-bold">Remarks</label>
					    <textarea type="text" class="form-control form-control-design  clsAlphaNoOnly" name="ho_remark2" value="{{ old('ho_remark2')}}" placeholder="Remarks..."></textarea>
					    @error('ho_remark2')<div class="text-error">{{ $message }}</div>@enderror
					</div>

				
					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Claim Status</label>
					    <select class="form-control form-control-design  form-select" name="cliam_status" >
					    	<option value="">Select</option>
					    	@foreach($claimstatus as $key=>$value)
					    	   <option {{ ( old('cliam_status' , 'Pending From Branch')==$value->claim_status)?'selected':''}} value="{{$value->claim_status}}">{{$value->claim_status}}</option>
					    	@endforeach
					    </select>
					    @error('cliam_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">CAS Status</label>
					    <select class="form-control form-control-design  form-select" name="cas_status">
					    	<option value="">Select</option>
					    	<option {{ ( old('cas_status')=='CAS Process')?'selected':''}} value="CAS Process">CAS Process</option>
					    </select>
					    @error('cas_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC/RL Status</label>
					    <select class="form-control form-control-design  form-select" name="rl_status" >
					    	<option value="">Select</option>
					    	@foreach($rlStat as $stat)
					    	  <option {{ (old('rl_status')==$stat->rl_status)?'selected':'' }} value="{{$stat->rl_status}}">{{$stat->rl_status}}</option>
		                    @endforeach
		                    @error('rl_status')<div class="text-error">{{ $message }}</div>@enderror
					    </select>
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Notification Number</label>
					    <input type="text" class="form-control form-control-design clsAlphaNoOnly" name="notification_number" value="{{ old('notification_number')}}" placeholder="Enter Notification Number">
					    @error('notification_number')<div class="text-error">{{ $message }}</div>@enderror
					</div>


        		</div>
        	</div>
        </div>


        <div class="card mt-3">
        	<div class="card-header label-font-header bg-card-header text-white">Settlement Details</div>
        	<div class="card-body">
        		<div class="row">

        			<div class="col-3 mb-3">
					    <label class="form-label label-bold">Loan Amount</label>
					    <input type="text" class="form-control form-control-design  number-with-format" name="loan_amount" value="{{ old('loan_amount')}}" placeholder="Enter Loan Amount">
					    @error('loan_amount')<div class="text-error">{{ $message }}</div>@enderror
					</div>

        			<div class="col-3 mb-3">
					    <label class="form-label label-bold">Loan Outstanding Amt</label>
					    <input type="text" class="form-control form-control-design  number-with-format" name="loan_outstanding" value="{{ old('loan_outstanding')}}" placeholder="Enter Loan Outstanding Amount">
					    @error('loan_outstanding')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Payable to Nominee</label>
					    <input type="text" class="form-control form-control-design  number-with-format" name="payable_to_nominee" value="{{ old('payable_to_nominee')}}" placeholder="Enter the Amount Payable to Nominee">
					    @error('payable_to_nominee')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Date of Settlement</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="settlement_date" value="{{ old('settlement_date')}}" max="{{ date('Y-m-d')}}">
					    @error('settlement_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>


					<div class="col-3 mb-3">
					    <label class="form-label label-bold">NEFT Rejection Date</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="neft_rejection_date" value="{{ old('neft_rejection_date')}}" max="{{ date('Y-m-d')}}">
					    @error('neft_rejection_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">NEFT Reason For Rejection</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="neft_rejection_reason" value="{{ old('neft_rejection_reason')}}" placeholder="Enter Reason for NEFT Rejection">
					    @error('neft_rejection_reason')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Final Settlement Date</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="final_settlement_date" value="{{ old('final_settlement_date')}}" max="{{ date('Y-m-d')}}">
					    @error('final_settlement_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>
					<div class="col-3"></div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">UTRN of MPH</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="utrn_mph" value="{{ old('utrn_mph')}}" placeholder="Enter UTRN of MPH">
					    @error('utrn_mph')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">UTRN of Nominee</label>
					    <input type="type" class="form-control form-control-design  clsAlphaNoOnly" name="utrn_nominee" value="{{ old('utrn_nominee')}}"  placeholder="Enter UTRN of Nominee">
					    @error('utrn_nominee')<div class="text-error">{{ $message }}</div>@enderror
					</div>
        	    </div>
        	</div>    		
        </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header bg-card-header text-white">Recovery Details</div>
        	<div class="card-body">
        		<div class="row">
        			<div class="col-3 mb-3">
					    <label class="form-label label-bold">Recovery Status</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="recovery_status" value="{{ old('recovery_status')}}" placeholder="Enter Recovery Status">
					    @error('recovery_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Recoveries</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="recoveries" value="{{ old('recoveries')}}" placeholder="Enter Recoveries">
					    @error('recoveries')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Bounced SPDC No</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="bounced_chq_no" value="{{ old('bounced_chq_no')}}" placeholder="Enter Bounced SPDC Number">
					    @error('bounced_chq_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Deposit Date</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="chq_deposit_date" value="{{ old('chq_deposit_date')}}" max="{{ date('Y-m-d')}}">
					    @error('chq_deposit_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Bounced Date</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="bounced_chq_date" value="{{ old('bounced_chq_date')}}" max="{{ date('Y-m-d')}}">
					    @error('bounced_chq_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Bounced Reason</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="bounced_chq_reason" value="{{ old('bounced_chq_reason')}}" placeholder="Enter reason for SPDC Bounce">
					    @error('bounced_chq_reason')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Recovered Amount</label>
					    <input type="text" class="form-control form-control-design  numbersonly" name="recovered_amount" value="{{ old('recovered_amount')}}" placeholder="Enter Rcovered Amount">
					    @error('recovered_amount')<div class="text-error">{{ $message }}</div>@enderror
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
					    <input type="date" class="form-control form-control-design  valid-date" name="ack_rec_date" value="{{ old('ack_rec_date')}}">
					    @error('ack_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">SPDC Received Date</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="spdc_rec_date" value="{{ old('spdc_rec_date')}}">
					    @error('spdc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Packet Number</label>
					    <input type="text" class="form-control form-control-design numbersonly" name="pkt_no" value="{{ old('pkt_no')}}" placeholder="Enter Packet Number">
					    @error('pkt_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>


					
        	    </div>
        	</div>    		
        </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header bg-card-header text-white">Write-Off Details</div>
        	<div class="card-body">
        		<div class="row">
        			<div class="col-3 mb-3">
					    <label class="form-label label-bold">Write off Received Date</label>
					    <input type="date" class="form-control form-control-design  valid-date" name="write_off_rec" value="{{ old('write_off_rec')}}">
					    @error('write_off_rec')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Write off Status</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="write_off_status" value="{{ old('write_off_status')}}" placeholder="Enter Write off status">
					    @error('write_off_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Handed Over to Business Head</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="handed_to_bh" value="{{ old('handed_to_bh')}}" placeholder="Handed over to Business Head">
					    @error('handed_to_bh')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label label-bold">Handed Over to Credit</label>
					    <input type="text" class="form-control form-control-design  clsAlphaNoOnly" name="handed_to_credit" value="{{ old('handed_to_credit')}}" placeholder="Handed over to credit">
					    @error('handed_to_credit')<div class="text-error">{{ $message }}</div>@enderror
					</div>
        	    </div>
        	</div>    		
        </div>

		

		<div class="d-flex mt-3">
			<div class="ms-auto">
				
				<button type="submit" class="btn btn-sm btn-success btn-text p-2">Submit</button>

			</div>
	    </div>

       </form>

       <div class="floating-buttons">
		    <button id="scrollTopBtn" title="Go to top">↑</button>
		    <button id="scrollBottomBtn" title="Go to bottom">↓</button>
		</div>

		</div>

<script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
 const coveredInput = document.querySelector('input[name="policy_covered_date"]');
    const tenureInput = document.querySelector('input[name="loan_tenure"]');
    const expiryInput = document.querySelector('input[name="policy_expiry_date"]');
    

    function calculateExpiryDate() {
       const coveredDate = new Date(coveredInput.value);
        const tenureMonths = parseInt(tenureInput.value);

        if (!isNaN(coveredDate.getTime()) && !isNaN(tenureMonths)) {
            const expiryDate = new Date(coveredDate);
            expiryDate.setMonth(expiryDate.getMonth() + tenureMonths);

            // Subtract one day
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

          //  ageInput.value = `${years} year${years !== 1 ? 's' : ''} ${months} month${months !== 1 ? 's' : ''}`;
             ageInput.value = `${years}`;
        } else {
            ageInput.value = '';
        }
    });
    
    const intimationReceivedDateInput = document.querySelector('input[name="intimation_date"]');
    const documentReceivedDateInput = document.querySelector('input[name="doc_rec_date"]');
    const documentsubmissionDateInput = document.querySelector('input[name="submit_to_partner_date"]');
    const documentre_submissionDateInput = document.querySelector('input[name="re_submit_to_partner_date"]');

  //  coveredInput.addEventListener('change', RestrictIntimationDate);

    /*function RestrictIntimationDate() {
       intimationReceivedDateInput.min = coveredInput.value;
    }*/

    function RestrictDocReceivedDate() {
       documentReceivedDateInput.min = intimationReceivedDateInput.value;
    }
    intimationReceivedDateInput.addEventListener('change', RestrictDocReceivedDate);

     function RestrictresubmissiondDate() {
        documentre_submissionDateInput.min = documentsubmissionDateInput.value;
    }
    
    documentsubmissionDateInput.addEventListener('change', RestrictresubmissiondDate);


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

</script>	
    
@endsection