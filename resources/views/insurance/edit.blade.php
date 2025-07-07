@extends('layouts.insurance-app')
@section('content')

<div class="container">
	<div class="d-flex py-4">
		<label class="label-font-header">Update Insurance Form - {{ $data->utrn }}</label>

        
		<div class="ms-auto">
			<a target="_blank" href="{{ route('download_claim_form',encrypt($data->id))}}"><button class="btn btn-sm btn-warning btn-text p-2">Download Claim Form</button> </a>

			<a target="_blank" href="{{ route('download_checklist',encrypt($data->id))}}"><button class="btn btn-sm btn-info btn-text p-2">Download Checklist</button> </a>
			
			<a href="{{ route('insurance_list')}}"><button class="btn btn-sm btn-dark btn-text p-2">Go Back</button> </a>
		</div>
	</div>

	<div class="py-2">
		<div class="row">
			
			<div class="col-3">
				<button class="form-control btn btn-sm btn-secondary btn-toggle p-2 card-design" id="ho"  value="ho">Head Office </button>
			</div>
           
			<div class="col-3">
				<button class="form-control btn-secondary btn btn-sm btn-toggle p-2 card-design" id="bo"  value="bo">Branch Office </button>
			</div>

			<!-- <div class="col-3">
				<button class="form-control btn-secondary btn btn-sm btn-toggle p-2 card-design"  value="cl">Check List </button>
			</div> -->
		</div>

		@if(Session::has('success'))
		 <script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">
		  var mesage = '{{ session('success') }}';
		  Swal.fire({
		        title: 'Message',
		        text: mesage,
		        icon: 'success',  
		        confirmButtonText: 'OK'
		        }).then((result) => {
	            if (result.isConfirmed) {
	                // 👇 Redirect to another URL
	                window.location.href = "{{ url('/insurance/claim_forms') }}";
	            }
		    });
		 </script>
		 
		@endif

		@if(Session::has('failure'))
		 <script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">
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
	    <fieldset {{ (auth::user()->branch_id == '1100')?'':'disabled'}}>

	    <div class="card mt-3">
        	<div class="card-header label-font-header border-dark">Lead Information</div>
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
        	<div class="card-header label-font-header">Policy Imformation</div>
        	<div class="card-body">
        		<div class="row">
        		  <div class="row">
					<div class="col-3 mb-3">
					    <label class="form-label">Region</label>
					    <select class="form-control form-select" name="region"  >
					    	<option value="">Select</option>
					    	<option {{($data->region == 'South')?'selected':''}} value="South" >South</option>
					    	<option {{($data->region == 'North')?'selected':''}} value="North">North</option>
					    	<option {{($data->region == 'East')?'selected':''}} value="East">East</option>
					    	<option {{($data->region == 'West')?'selected':''}} value="West">West</option>	
					    </select>
					    @error('region')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Branch ID-Name</label>
					    <input type="text" class="form-control" name="branch" value="{{ $data->branch }}" >
					    @error('branch')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Partner</label>
					    <select class="form-control form-select" name="partner"  >
					    	<option value="">Select</option>
					    	@foreach($partners as $key=>$value)
					    	   <option {{($data->partner == $value->partner)?'selected':''}} value="{{$value->partner}}">{{$value->partner}}</option>
					    	@endforeach
					    </select>
					    @error('partner')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Product</label>
					    <select class="form-control form-select" name="product"  >
					    	<option value="">Select</option>
					    	@foreach($products as $key=>$value)
					    	   <option {{($data->product== $value->product)?'selected':''}} value="{{$value->product}}">{{$value->product}}</option>
					    	@endforeach
					    </select>
					    @error('product')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Policy Number</label>
					    <input type="text" class="form-control numbersonly" name="policy_number" value="{{ $data->policy_number}}">
					    @error('policy_number')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Customer ID</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="cust_id" value="{{ $data->cust_id}}">
					    @error('cust_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">ACTUAL ID</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="actual_id" value="{{ $data->actual_id}}">
					    @error('actual_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Deceased Name</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="deceased_name" value="{{ $data->deceased_name}}">
					    @error('deceased_name')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">MP NO</label>
					    <input type="text" class="form-control numbersonly" name="mp_no" value="{{ $data->mp_no}}">
					    @error('mp_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Policy Covered</label>
					    <input type="date" class="form-control valid-date" name="policy_covered_date" value="{{ $data->policy_covered_date}}">
					    @error('policy_covered_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Loan Tenure</label>
					    <input type="text" class="form-control numbersonly" name="loan_tenure" value="{{ $data->loan_tenure}}" maxlength="3">
					    @error('loan_tenure')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Policy Expired Date</label>
					    <input type="date" class="form-control valid-date" name="policy_expiry_date" value="{{ $data->policy_expiry_date}}">
					    @error('policy_expiry_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of Death</label>
					    <input type="date" class="form-control valid-date" name="date_of_death" value="{{ $data->date_of_death}}">
					    @error('date_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>


					<div class="col-3 mb-3">
					    <label class="form-label">Gender</label>
					    <select class="form-control form-select" name="gender">
					    	<option value="">Select</option>
					    	<option {{ ( $data->gender=='Male')?'selected':''}}  value="Male">Male</option>
					    	<option {{ ( $data->gender=='Female')?'selected':''}}  value="Female">Female</option>
					    </select>
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Deceased</label>
					    <select class="form-control form-select" name="deceased" >
					    	<option value="">Select</option>
					    	@foreach($deceased as $key=>$value)
					    	   <option {{ ( $data->deceased==$value)?'selected':''}} 
					    	    value="{{$value}}">{{$value}}</option>
					    	@endforeach
					    </select>
					    @error('deceased')<div class="text-error">{{ $message }}</div>@enderror

					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date Of Death Intimation</label>
					    <input type="date" class="form-control valid-date" name="intimation_date" value="{{ $data->intimation_date}}">
					    @error('intimation_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Age</label>
					    <input type="text" class="form-control numbersonly" name="age" value="{{ $data->age}}" maxlength="3">
					    @error('age')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Place of Death</label>
					    <select class="form-control form-select" name="place_of_death">
					    	<option>Select</option>
					    	@foreach($placeofdeath as $key=>$value)
					    	   <option {{ ( $data->place_of_death==$value->place)?'selected':''}}  value="{{$value->place}}">{{$value->place}}</option>
					    	@endforeach
					    </select>
					    @error('place_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Cause of Death</label>
					    <select class="form-control form-select" name="cause_of_death" >
					    	<option>Select</option>
					    	@foreach($deathcause as $key=>$value)
					    	   <option  {{ ( $data->cause_of_death==$value->cause)?'selected':''}}  value="{{$value->cause}}">{{$value->cause}}</option>
					    	@endforeach
					    </select>
					    @error('cause_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Loan Account ID</label>
					    <input type="text" class="form-control numbersonly" name="load_acc_id" value="{{ $data->load_acc_id}}">
					    @error('load_acc_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Claim Amount</label>
					    <input type="text" class="form-control number-input number-with-format" name="claim_amount" value="{{ $data->claim_amount}}">
					    @error('claim_amount')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of Birth</label>
					    <input type="date" class="form-control valid-date" name="dob" value="{{ $data->dob}}">
					    @error('dob')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Claim Status</label>
					    <select class="form-control form-select" name="cliam_status" >
					    	<option value="">Select</option>
					    	@foreach($claimstatus as $key=>$value)
					    	   <option {{ ( $data->cliam_status==$value->claim_status)?'selected':''}} value="{{$value->claim_status}}">{{$value->claim_status}}</option>
					    	@endforeach
					    </select>
					    @error('cliam_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">CAS Status</label>
					    <select class="form-control form-select" name="cas_status">
					    	<option value="">Select</option>
					    	<option {{ ( $data->cas_status=='CAS Process')?'selected':''}} value="CAS Process">CAS Process</option>
					    	<option {{ ( $data->cas_status=='PDC Process')?'selected':''}} value="PDC Process">PDC Process</option>
					    </select>
					    @error('cas_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>	

					<div class="col-3 mb-3">
					    <label class="form-label">SPDC/RL Status</label>
					    <select class="form-control form-select" name="rl_status" >
					    	<option value="">Select</option>
					    	@foreach($rlStat as $key=>$stat)
					    	  <option {{ ($data->rl_status==$stat->rl_status)?'selected':'' }} value="{{$stat->rl_status}}">{{$stat->rl_status}}</option>
		                    @endforeach
		                    @error('rl_status')<div class="text-error">{{ $message }}</div>@enderror
					    </select>
					</div>


					<div class="col-3 mb-3">
					    <label class="form-label">Packet Number</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="pkt_no" value="{{ $data->pkt_no}}">
					    @error('pkt_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>
        	    </div>
        	</div>    		
        </div>
       </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header">Claim Status</div>
        	<div class="card-body">
        		<div class="row">
        			<div class="col-6 mb-3">
					    <label class="form-label">HO Remarks</label>
					    <textarea class="form-control clsAlphaNoOnly" name="ho_remark">{{ $data->ho_remark}}</textarea>
					    @error('ho_remark')<div class="text-error">{{ $message }}</div>@enderror
					</div>
					<div class="col-6 mb-3"></div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of document received</label>
					    <input type="date" class="form-control valid-date" name="doc_rec_date" value="{{ $data->doc_rec_date}}">
					    @error('doc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Processed By</label>
					    <select class="form-control form-select" name="processed_by">
					    	<option value="">Select</option>
					    	@foreach($procesedby as $proc)
					    	  <option {{ ($data->processed_by==$proc)?'selected':'' }} value="{{$proc}}">{{$proc}}</option>
		                    @endforeach
		                    @error('processed_by')<div class="text-error">{{ $message }}</div>@enderror
					    </select>
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of submision to partner</label>
					    <input type="date" class="form-control valid-date" name="submit_to_partner_date" value="{{ $data->submit_to_partner_date}}">
					    @error('submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-6 mb-3">
					    <label class="form-label">Remarks</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="ho_remark2" value="{{ $data->ho_remark2}}">
					    @error('ho_remark2')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of re-submision to partner</label>
					    <input type="date" class="form-control valid-date" name="re_submit_to_partner_date" value="{{ $data->re_submit_to_partner_date}}">
					    @error('re_submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>
        	    </div>
        	</div>    		
        </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header">Settlement Details</div>
        	<div class="card-body">
        		<div class="row">
        			<div class="col-3 mb-3">
					    <label class="form-label">Loan Outstanding Amt</label>
					    <input type="text" class="form-control number-input number-with-format" name="loan_outstanding" value="{{ $data->loan_outstanding}}">
					    @error('loan_outstanding')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Payable to Nominee</label>
					    <input type="text" class="form-control number-input number-with-format" name="payable_to_nominee" value="{{ $data->payable_to_nominee}}">
					    @error('payable_to_nominee')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Nominee Name</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="nominee_name" value="{{ $data->nominee_name}}">
					    @error('nominee_name')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Nominee Contact No</label>
					    <input type="text" class="form-control numberonly" name="nominee_number" value="{{ $data->nominee_number}}" minlength="10" maxlength="10">
					    @error('nominee_number')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Relationship</label>
					    <select class="form-control form-select" name="relationship" >
					    	<option value="">Select</option>
					    	@foreach($relationship as $key=>$value)
					    	   <option {{ ( $data->relationship==$value->relationship)?'selected':''}} value="{{$value->relationship}}">{{$value->relationship}}</option>
					    	@endforeach
					    </select>
					    @error('relationship')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Acknowledgement Received Date</label>
					    <input type="date" class="form-control valid-date" name="ack_rec_date" value="{{ $data->ack_rec_date}}">
					    @error('ack_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of settlement</label>
					    <input type="date" class="form-control valid-date" name="settlement_date" value="{{ $data->settlement_date}}">
					    @error('settlement_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">NEFT Rejection Date</label>
					    <input type="date" class="form-control valid-date" name="neft_rejection_date" value="{{ $data->neft_rejection_date}}">
					    @error('neft_rejection_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">NEFT Reason For Rejection</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="neft_rejection_reason" value="{{ $data->neft_rejection_reason}}">
					    @error('neft_rejection_reason')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Final Settlement Date</label>
					    <input type="date" class="form-control valid-date" name="final_settlement_date" value="{{ $data->final_settlement_date}}">
					    @error('final_settlement_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

        	    </div>
        	</div>    		
        </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header">Recovery Details</div>
        	<div class="card-body">
        		<div class="row">
        			<div class="col-3 mb-3">
					    <label class="form-label">Recovery Status</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="recovery_status" value="{{ $data->recovery_status}}">
					    @error('recovery_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Bounced CHQ No</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="bounced_chq_no" value="{{ $data->bounced_chq_no}}">
					    @error('bounced_chq_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">CHQ Bounced Date</label>
					    <input type="date" class="form-control valid-date" name="bounced_chq_date" value="{{ $data->bounced_chq_date}}">
					    @error('bounced_chq_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">CHQ Bounced reason</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="bounced_chq_reason" value="{{ $data->bounced_chq_reason}}">
					    @error('bounced_chq_reason')<div class="text-error">{{ $message }}</div>@enderror
					</div>
        	    </div>
        	</div>    		
        </div>

        <div class="card mt-3">
        	<div class="card-header label-font-header">Write-Off Details</div>
        	<div class="card-body">
        		<div class="row">
        			<div class="col-3 mb-3">
					    <label class="form-label">Write off received</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="write_off_rec" value="{{ $data->write_off_rec}}">
					    @error('write_off_rec')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Write off status</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="write_off_status" value="{{ $data->write_off_status}}">
					    @error('write_off_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Handed over to Business Head</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="handed_to_bh" value="{{ $data->handed_to_bh}}">
					    @error('handed_to_bh')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Handed over to credit</label>
					    <input type="text" class="form-control clsAlphaNoOnly" name="handed_to_credit" value="{{ $data->handed_to_credit}}">
					    @error('handed_to_credit')<div class="text-error">{{ $message }}</div>@enderror
					</div>
        	    </div>
        	</div>    		
        </div>
        
        </fieldset>

        @if(auth::user()->branch_id == '1100')
		<div class="d-flex mt-3">
			<div class="ms-auto">
				<button type="submit" class="btn btn-sm btn-danger btn-text p-2">Update</button>
			</div>
	    </div>
	    @endif

        </form>
        
		</div>
       
     <!-- BO -->

       
		<div class="py-3 d-none" id="branch_off">
			<form method="POST" action="{{route('save_nominee_details')}}">
			@csrf
			<fieldset >
			<div class="row">
				<div class="col-3 mb-3">
				    <label class="form-label">Nominee Name as per Bank Records</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="nominee_name_bank" value="{{$nomineedata->nominee_name_bank ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Name of the Bank</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="bank_name"  value="{{$nomineedata->bank_name ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Bank A/c Number</label>
				    <input type="text" class="form-control numbersonly" name="acc_number"  value="{{$nomineedata->acc_number ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">IFSC Code</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="ifsc"  value="{{$nomineedata->ifsc ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Bank Branch Name</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="branch_name"  value="{{$nomineedata->branch_name ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">SPDC-Bank Name</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="spdc_bank_name"  value="{{$nomineedata->spdc_bank_name ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">SPDC-Chq Number</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="spdc_chk_no"  value="{{$nomineedata->spdc_chk_no ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Courier Name</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="courier_name"  value="{{$nomineedata->courier_name ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">POD Number</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="pod_no"  value="{{$nomineedata->pod_no ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Cheque Sent Date</label>
				    <input type="date" class="form-control valid-date" name="cheq_sent_date"  value="{{$nomineedata->cheq_sent_date ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Remarks</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="bo_remarks"  value="{{$nomineedata->bo_remarks ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Maker at Branch</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="bo_maker"  value="{{$nomineedata->bo_maker ?? ''}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Checker at Branch</label>
				    <input type="text" class="form-control clsAlphaNoOnly" name="bo_checker"  value="{{$nomineedata->bo_checker ?? ''}}">
				</div>

			</div>
            <input type="hidden" name="lead_id" value="{{ encrypt($data->id) }}">
		
            </fieldset>
           
			<div class="d-flex">
				<div class="ms-auto">
					<button type="submit" class="btn btn-sm btn-danger btn-text p-2">Update</button>
				</div>
		    </div>
		    
        </form>
		</div>

		
      <!-- BO -->

       
       <input type="hidden" id="usertype" value="{{ auth::user()->branch_id}}">
	</div>
</div>

<script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">

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
    let numberInputs = document.querySelectorAll(".number-with-format");

    numberInputs.forEach(function (input) {
        // Restrict input to numbers and a single decimal point
        input.addEventListener("keypress", function (event) {
            numbersonly(event);
        });

        // Format input on change
        input.addEventListener("input", function () {
            formatNumber(this);
        });
    });


     let nameInputs = document.querySelectorAll(".nameonly");

    nameInputs.forEach(function (input) {
    	
        // Restrict input to numbers and a single decimal point
       input.addEventListener("input", function (event) {
            validateLength(event);
        });
        // Restrict input to alphanumeric characters
        input.addEventListener("keypress", function (event) {
            //validateName(event);
            validateNamewithNumber(event);
        });

    });

    let numberonlyInputs = document.querySelectorAll(".numbersonly");

    numberonlyInputs.forEach(function (input) {
    	
       
        input.addEventListener("keypress", function (event) {
            numbersonly(event);
        });

    });

    let datesInputs = document.querySelectorAll(".valid-date");

    datesInputs.forEach(function (input) {
    	
       
        input.addEventListener("keypress", function (event) {
            event.preventDefault();
        });

    });

    let alphaInputs = document.querySelectorAll(".clsAlphaNoOnly");

        alphaInputs.forEach(function (input) {
      
       
        input.addEventListener("keypress", function (event) {
            clsAlphaNoOnly(event);
        });

    });
});

document.querySelectorAll(".number-input").forEach(inputElement => {
      // Format and display existing value on load
      inputElement.value = transformation(inputElement.value);

      inputElement.addEventListener("input", function(event) {
        const cursorPosition = inputElement.selectionStart;

        // Remove commas and get raw value
        const rawValue = inputElement.value.replace(/,/g, "");
        const formattedValue = transformation(rawValue);

        // Update the input value with the formatted number
        inputElement.value = formattedValue;

        // Restore cursor position based on digits before the cursor
        const digitsBeforeCursor = rawValue.slice(0, cursorPosition).replace(/[^0-9]/g, "").length;
        let newCursorPosition = 0;
        let digitCount = 0;

        for (let i = 0; i < formattedValue.length; i++) {
          if (/\d/.test(formattedValue[i])) {
            digitCount++;
          }
          if (digitCount === digitsBeforeCursor) {
            newCursorPosition = i + 1;
            break;
          }
        }

        inputElement.setSelectionRange(newCursorPosition, newCursorPosition);
      });
    });

function transformation(input) {
      input = input.replace(/,/g, ""); // Remove existing commas
      const lastThreeDigits = input.slice(-3); // Extract the last 3 digits
      const restOfTheNumber = input.slice(0, -3); // Extract the remaining part
      
      if (restOfTheNumber !== "") {
        return restOfTheNumber.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + lastThreeDigits;
      }
      return lastThreeDigits;
    }


</script>
@endsection