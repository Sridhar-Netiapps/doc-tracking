@extends('layouts.insurance-app')
@section('content')

<div class="container">
	<div class="d-flex py-4">
		<label class="label-font-header">Update Insurance Form </label>

        
		<div class="ms-auto">
			@if(auth::user()->branch_id != '1100')
			<a href="{{ route('download_claim_form',encrypt($data->id))}}"><button class="btn btn-sm btn-warning btn-text p-2">Download Claim Form</button> </a>
			@endif

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

			<div class="col-3">
				<button class="form-control btn-secondary btn btn-sm btn-toggle p-2 card-design"  value="cl">Check List </button>
			</div>
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
		        icon: 'success',  
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
		        				<label>First Name </label><br>
		        				<label>Last Name</label><br>
		        				<label>Creation Date</label><br>
		        				<label>Modified Date</label>
		        			</div>
		        			<div class="col">
		        				<strong>{{ $data->hocreator->first_name}}</strong><br>
		        				<strong>{{ $data->hocreator->last_name}}</strong><br>
		        				<strong>{{ date('d M Y H:i:s',strtotime($data->created_at)) }}</strong><br>
		        				<strong>{{ date('d M Y H:i:s',strtotime($data->updated_at)) }}</strong>
		        			</div>
		        			<div class="col text-end">
		        				<label>Lead Number</label><br>
		        				<label>Assigned To </label><br>
		        				<label>POD Number</label>
		        			</div>
		        			<div class="col">
		        				<strong>{{ $data->utrn}}</strong><br>
		        				<strong></strong><br>
		        				<strong>{{ $nomineedata->pod_no}}</strong>
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
					    	   <option {{($data->partner == $value->id)?'selected':''}} value="{{$value->id}}">{{$value->partner}}</option>
					    	@endforeach
					    </select>
					    @error('partner')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Product</label>
					    <select class="form-control form-select" name="product"  >
					    	<option value="">Select</option>
					    	@foreach($products as $key=>$value)
					    	   <option {{($data->product== $value->id)?'selected':''}} value="{{$value->id}}">{{$value->product}}</option>
					    	@endforeach
					    </select>
					    @error('product')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Policy Number</label>
					    <input type="text" class="form-control numberonly" name="policy_number" value="{{ $data->policy_number}}">
					    @error('policy_number')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Customer ID</label>
					    <input type="text" class="form-control numberonly" name="cust_id" value="{{ $data->cust_id}}">
					    @error('cust_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">ACTUAL ID</label>
					    <input type="text" class="form-control numberonly" name="actual_id" value="{{ $data->actual_id}}">
					    @error('actual_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Deceased Name</label>
					    <input type="text" class="form-control numberonly" name="deceased_name" value="{{ $data->deceased_name}}">
					    @error('deceased_name')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">MP NO</label>
					    <input type="text" class="form-control numberonly" name="mp_no" value="{{ $data->mp_no}}">
					    @error('mp_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Policy Covered</label>
					    <input type="date" class="form-control" name="policy_covered_date" value="{{ $data->policy_covered_date}}">
					    @error('policy_covered_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Loan Tenure</label>
					    <input type="text" class="form-control numberonly" name="loan_tenure" value="{{ $data->loan_tenure}}">
					    @error('loan_tenure')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Policy Expired Date</label>
					    <input type="date" class="form-control" name="policy_expiry_date" value="{{ $data->policy_expiry_date}}">
					    @error('policy_expiry_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of Death</label>
					    <input type="date" class="form-control" name="date_of_death" value="{{ $data->date_of_death}}">
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
					    <input type="date" class="form-control" name="intimation_date" value="{{ $data->intimation_date}}">
					    @error('intimation_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Age</label>
					    <input type="text" class="form-control numberonly" name="age" value="{{ $data->age}}">
					    @error('age')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Place of Death</label>
					    <select class="form-control form-select" name="place_of_death">
					    	<option>Select</option>
					    	@foreach($placeofdeath as $key=>$value)
					    	   <option {{ ( $data->place_of_death==$value->id)?'selected':''}}  value="{{$value->id}}">{{$value->place}}</option>
					    	@endforeach
					    </select>
					    @error('place_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Cause of Death</label>
					    <select class="form-control form-select" name="cause_of_death" >
					    	<option>Select</option>
					    	@foreach($deathcause as $key=>$value)
					    	   <option  {{ ( $data->cause_of_death==$value->id)?'selected':''}}  value="{{$value->id}}">{{$value->cause}}</option>
					    	@endforeach
					    </select>
					    @error('cause_of_death')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Loan Account ID</label>
					    <input type="text" class="form-control numberonly" name="load_acc_id" value="{{ $data->load_acc_id}}">
					    @error('load_acc_id')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Claim Amount</label>
					    <input type="text" class="form-control numberonly" name="claim_amount" value="{{ $data->claim_amount}}">
					    @error('claim_amount')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of Birth</label>
					    <input type="date" class="form-control" name="dob" value="{{ $data->dob}}">
					    @error('dob')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Claim Status</label>
					    <select class="form-control form-select" name="cliam_status" >
					    	<option value="">Select</option>
					    	@foreach($claimstatus as $key=>$value)
					    	   <option {{ ( $data->cliam_status==$value->id)?'selected':''}} value="{{$value->id}}">{{$value->claim_status}}</option>
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
					    	  <option {{ ($data->rl_status==$stat->id)?'selected':'' }} value="{{$stat->id}}">{{$stat->rl_status}}</option>
		                    @endforeach
		                    @error('rl_status')<div class="text-error">{{ $message }}</div>@enderror
					    </select>
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
					    <label class="form-label">Packet Number</label>
					    <input type="text" class="form-control numberonly" name="pkt_no" value="{{ $data->pkt_no}}">
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
					    <textarea class="form-control" name="ho_remark">{{ $data->ho_remark}}</textarea>
					    @error('ho_remark')<div class="text-error">{{ $message }}</div>@enderror
					</div>
					<div class="col-6 mb-3"></div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of document received</label>
					    <input type="date" class="form-control" name="doc_rec_date" value="{{ $data->doc_rec_date}}">
					    @error('doc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of submision to partner</label>
					    <input type="date" class="form-control" name="submit_to_partner_date" value="{{ $data->submit_to_partner_date}}">
					    @error('submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-6 mb-3">
					    <label class="form-label">Remarks</label>
					    <input type="text" class="form-control numberonly" name="ho_remark2" value="{{ $data->ho_remark2}}">
					    @error('ho_remark2')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of re-submision to partner</label>
					    <input type="date" class="form-control" name="re_submit_to_partner_date" value="{{ $data->re_submit_to_partner_date}}">
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
					    <input type="text" class="form-control numberonly" name="loan_outstanding" value="{{ $data->loan_outstanding}}">
					    @error('loan_outstanding')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Payable to Nominee</label>
					    <input type="text" class="form-control numberonly" name="payable_to_nominee" value="{{ $data->payable_to_nominee}}">
					    @error('payable_to_nominee')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Nominee Name</label>
					    <input type="text" class="form-control" name="nominee_name" value="{{ $data->nominee_name}}">
					    @error('nominee_name')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Nominee Contact No</label>
					    <input type="text" class="form-control numberonly" name="nominee_number" value="{{ $data->nominee_number}}">
					    @error('nominee_number')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Relationship</label>
					    <select class="form-control form-select" name="relationship" >
					    	<option value="">Select</option>
					    	@foreach($relationship as $key=>$value)
					    	   <option {{ ( $data->relationship==$value->id)?'selected':''}} value="{{$value->id}}">{{$value->relationship}}</option>
					    	@endforeach
					    </select>
					    @error('relationship')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Acknowledgement Received Date</label>
					    <input type="date" class="form-control numberonly" name="ack_rec_date" value="{{ $data->ack_rec_date}}">
					    @error('ack_rec_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Date of settlement</label>
					    <input type="date" class="form-control" name="settlement_date" value="{{ $data->settlement_date}}">
					    @error('settlement_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">NEFT Rejection Date</label>
					    <input type="date" class="form-control" name="neft_rejection_date" value="{{ $data->neft_rejection_date}}">
					    @error('neft_rejection_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">NEFT Reason For Rejection</label>
					    <input type="text" class="form-control" name="neft_rejection_reason" value="{{ $data->neft_rejection_reason}}">
					    @error('neft_rejection_reason')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Final Settlement Date</label>
					    <input type="date" class="form-control" name="final_settlement_date" value="{{ $data->final_settlement_date}}">
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
					    <input type="text" class="form-control numberonly" name="recovery_status" value="{{ $data->recovery_status}}">
					    @error('recovery_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Bounced CHQ No</label>
					    <input type="text" class="form-control" name="bounced_chq_no" value="{{ $data->bounced_chq_no}}">
					    @error('bounced_chq_no')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">CHQ Bounced Date</label>
					    <input type="date" class="form-control" name="bounced_chq_date" value="{{ $data->bounced_chq_date}}">
					    @error('bounced_chq_date')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">CHQ Bounced reason</label>
					    <input type="text" class="form-control" name="bounced_chq_reason" value="{{ $data->bounced_chq_reason}}">
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
					    <input type="text" class="form-control" name="write_off_rec" value="{{ $data->write_off_rec}}">
					    @error('write_off_rec')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Write off status</label>
					    <input type="text" class="form-control" name="write_off_status" value="{{ $data->write_off_status}}">
					    @error('write_off_status')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Handed over to Business Head</label>
					    <input type="text" class="form-control" name="handed_to_bh" value="{{ $data->handed_to_bh}}">
					    @error('handed_to_bh')<div class="text-error">{{ $message }}</div>@enderror
					</div>

					<div class="col-3 mb-3">
					    <label class="form-label">Handed over to credit</label>
					    <input type="text" class="form-control" name="handed_to_credit" value="{{ $data->handed_to_credit}}">
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
			<fieldset {{ (auth::user()->branch_id == '1100')?'disabled':''}}>
			<div class="row">
				<div class="col-3 mb-3">
				    <label class="form-label">Nominee Name as per Bank Records</label>
				    <input type="text" class="form-control" name="nominee_name_bank" value="{{$nomineedata->nominee_name_bank}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Name of the Bank</label>
				    <input type="text" class="form-control" name="bank_name"  value="{{$nomineedata->bank_name}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Bank A/c Number</label>
				    <input type="text" class="form-control numberonly" name="acc_number"  value="{{$nomineedata->acc_number}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">IFSC Code</label>
				    <input type="text" class="form-control" name="ifsc"  value="{{$nomineedata->ifsc}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Bank Branch Name</label>
				    <input type="text" class="form-control" name="branch_name"  value="{{$nomineedata->branch_name}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">SPDC-Bank Name</label>
				    <input type="text" class="form-control" name="spdc_bank_name"  value="{{$nomineedata->spdc_bank_name}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">SPDC-Chq Number</label>
				    <input type="text" class="form-control" name="spdc_chk_no"  value="{{$nomineedata->spdc_chk_no}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Courier Name</label>
				    <input type="text" class="form-control" name="courier_name"  value="{{$nomineedata->courier_name}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">POD Number</label>
				    <input type="text" class="form-control" name="pod_no"  value="{{$nomineedata->pod_no}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Cheque Sent Date</label>
				    <input type="date" class="form-control" name="cheq_sent_date"  value="{{$nomineedata->cheq_sent_date}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Remarks</label>
				    <input type="text" class="form-control" name="bo_remarks"  value="{{$nomineedata->bo_remarks}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Maker at Branch</label>
				    <input type="text" class="form-control" name="bo_maker"  value="{{$nomineedata->bo_maker}}">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Checker at Branch</label>
				    <input type="text" class="form-control" name="bo_checker"  value="{{$nomineedata->bo_checker}}">
				</div>

			</div>
            <input type="hidden" name="lead_id" value="{{ encrypt($data->id) }}">
		
            </fieldset>
            @if(auth::user()->branch_id != '1100')
			<div class="d-flex">
				<div class="ms-auto">
					<button type="submit" class="btn btn-sm btn-danger btn-text p-2">Update</button>
				</div>
		    </div>
		    @endif
        </form>
		</div>

		
      <!-- BO -->

       <form method="POST" action="{{ route('save_claim_checklist')}}">
       	@csrf
		<div class="py-3 d-none" id="checklist">
		
			<div class="pagelayout">
		  	 <div class="contentmain">
		  	 	 <h2 class="headertext">Insurance Claim Document Checklist</h2>

		  	 	 <table class="twocol-table-noborder">
			  	 	<tr>
			  	 		<td class="col-70"><label class="label-font-bold">Branch ID & Name:  </label> <input type="text" name="branch_id" value="{{$data->branch}}" readonly> </td>
			  	 		<td class="col-30"><label class="label-font-bold"></label>
			  	 			<label class="label-font-bold">Sent Date:</label>
						      <input type="text" class="date-decoration" name="date" maxlength="2" size="2" name="dd" placeholder="DD" value="{{ date('d')}}" />
						      <strong>/</strong>
						      <input type="text" class="date-decoration" name="month" maxlength="2" size="2" name="mm" placeholder="MM"  value="{{ date('m')}}"/>
						      <strong>/</strong>
						      <input type="text" class="year-decoration" name="year" maxlength="4" size="4" name="yyyy" placeholder="YYYY"  value="{{ date('Y')}}"/>
			  	 		</td>
			  	 	</tr>
			  	 </table>
		         
			  	 <table class="twocol-table-noborder">
			  	 	<tr>
			  	 		<td class="col-70"><label class="label-font-bold">Customer ID: <input type="text" name="cust_id" value="{{$data->cust_id}}" readonly ></label> </td>
			  	 		<td class="col-30"><label class="label-font-bold">Deceased Name:</label>
			  	 			<input type="text" name="deceased_name" value="{{$data->deceased_name}}" readonly>
			  	 		</td>
			  	 	</tr>
			  	 </table>
			  	 @php
                   $name = json_decode($checklistdata->name ?? '',true);
                   $name_mismatch = json_decode($checklistdata->name_mismatch ?? '',true);
                   $age = json_decode($checklistdata->age ?? '',true);
                   $age_mismatch = json_decode($checklistdata->age_mismatch ?? '',true);
                   $customer_id = json_decode($checklistdata->customer_id ?? '',true);
                   $dod = json_decode($checklistdata->dod ?? '',true);
                   $is_mlc = json_decode($checklistdata->is_mlc ?? '',true);
                   $fir_attached = json_decode($checklistdata->fir_attached ?? '',true);
                   $death_certificate = json_decode($checklistdata->death_certificate ?? '',true);
                   $valid_certificate = json_decode($checklistdata->valid_certificate ?? '',true);
                   $doc_bajaj = json_decode($checklistdata->doc_bajaj ?? '',true);
                   $doc_death = json_decode($checklistdata->doc_death ?? '',true);
                   $doc_fir = json_decode($checklistdata->doc_fir ?? '',true);
                   $doc_proof = json_decode($checklistdata->doc_proof ?? '',true);
                   $doc_closure_request = json_decode($checklistdata->doc_closure_request ?? '',true);
                   $doc_ecs = json_decode($checklistdata->doc_ecs ?? '',true);
                   $docs_readable = json_decode($checklistdata->docs_readable ?? '',true);
			  	 @endphp

			  	 <table class="twocol-table margintop">
		           <tr class="text-center">
			  	 	<td class="col-7 tdchecklist"><span class="table-head-font ">Sl No</span></td>
			  	 	<td class="col-65"><span class="table-head-font">Particulars</span></td>
			  	 	<td class="col-7 tdchecklist"><label class="table-head-font">CCR/CRS/<br>Cashier</label></td>
			  	 	<td class="col-7 tdchecklist td-bg"><label class="table-head-font ">CRM/<br>PM</label></td>
			  	 	<td class="col-7 tdchecklist"><label class="table-head-font">HO <br>Maker</label></td>
			  	 	<td class="col-7 tdchecklist td-bg"><label class="table-head-font">HO <br>Checker</label></td>
		           </tr>
		           <tr>
				 	 <td class="col-7 tdchecklist lableslno" rowspan="2" ><label class="table-font">1</label></td>
				 	 <td class="col-65">
				    <span class="table-font">Deceased Name in Bajaj Claim form match with Death Certificate, Age / ID Proof, FIR/Post Mortem Report</span>
				 	 </td>
				 	 <td class="col-7 tdchecklist tdchecklist">
				 	 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="name[bm]" value="{{ (!empty($name) && ($name['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name[bm]" value="0"> @endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="name[bm]" value="1" 
				 	 	{{ !empty($name)? ($name['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
				 	 </td>
				 	 <td class="col-7 tdchecklist td-bg">
				 	 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="name[bc]" value="{{ (!empty($name) && ($name['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name[bc]" value="0">@endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="name[bc]" value="1" 
				 	 	{{ !empty($name)? ($name['bc'] == '1')?'checked':'': ''}}  {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
				 	 </td>
				 	 <td class="col-7 tdchecklist">
				 	 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="name[hm]" value="{{ (!empty($name) && ($name['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name[hm]" value="0"> @endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="name[hm]" value="1" 
				 	 	{{ !empty($name)? ($name['hm'] == '1')?'checked':'': ''}}  {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
				 	 </td>
				 	 <td class="col-7 tdchecklist td-bg">
				 	 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="name[hc]" value="{{ (!empty($name) && ($name['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name[hc]" value="0"> @endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="name[hc]" value="1" 
				 	 	{{ !empty($name)? ($name['hc'] == '1')?'checked':'': ''}}  {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
				 	 </td>
				   </tr>

				   <tr>
					 <td class="col-65">
					   <span class="table-font"><strong>If name is not matching,</strong> Need Court Affidavit (mentioning all the names)</span>
					 </td>
					 <td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="name_mismatch[bm]" value="{{ (!empty($name_mismatch) && ($name_mismatch['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name_mismatch[bm]" value="0"> @endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="name_mismatch[bm]" value="1" {{ !empty($name_mismatch)? ($name_mismatch['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
					 </td>
					 <td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="name_mismatch[bc]" value="{{ (!empty($name_mismatch) && ($name_mismatch['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name_mismatch[bc]" value="0">@endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="name_mismatch[bc]" value="1" {{ !empty($name_mismatch)? ($name_mismatch['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
					 </td>
					 <td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="name_mismatch[hm]" value="{{ (!empty($name_mismatch) && ($name_mismatch['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name_mismatch[hm]" value="0"> @endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="name_mismatch[hm]" value="1" {{ !empty($name_mismatch)? ($name_mismatch['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
					 </td>
					 <td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="name_mismatch[hc]" value="{{ (!empty($name_mismatch) && ($name_mismatch['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="name_mismatch[hc]" value="0"> @endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox"name="name_mismatch[hc]" value="1" {{ !empty($name_mismatch)? ($name_mismatch['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
					 </td>
				   </tr>

				    <tr>
				 	 <td class="col-7 tdchecklist lableslno" rowspan="2" ><label class="table-font">2</label></td>
				 	 <td class="col-65">
				    <span class="table-font">Deceased age (as per Bajaj Claim form) match with Death Certificate, Age / ID Proof, FIR
		                 or Post Mortem Report</span>
				 	 </td>
				 	 <td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="age[bm]" value="{{ (!empty($age) && ($age['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age[bm]" value="0"> @endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="age[bm]" value="1" {{ !empty($age)? ($age['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
				 	 </td>
				 	 <td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="age[bc]" value="{{ (!empty($age) && ($age['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age[bc]" value="0">@endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox"name="age[bc]" value="1" {{ !empty($age)? ($age['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
				 	 </td>
				 	 <td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="age[hm]" value="{{ (!empty($age) && ($age['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age[hm]" value="0"> @endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="age[hm]" value="1" {{ !empty($age)? ($age['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
				 	 </td>
				 	 <td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="age[hc]" value="{{ (!empty($age) && ($age['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age[hc]" value="0"> @endif
				 	 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="age[hc]" value="1" {{ !empty($age)? ($age['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
				 	 </td>
				   </tr>

				   <tr>
					 <td class="col-65">
					   <span class="table-font"><strong>If Age difference is 5 years (+ or -)</strong> Need Court Affidavit </span>
					 </td>
					 <td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="age_mismatch[bm]" value="{{ (!empty($age_mismatch) && ($age_mismatch['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age_mismatch[bm]" value="0"> @endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="age_mismatch[bm]" value="1" {{ !empty($age_mismatch)? ($age_mismatch['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
					 </td>
					 <td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="age_mismatch[bc]" value="{{ (!empty($age_mismatch) && ($age_mismatch['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age_mismatch[bc]" value="0">@endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="age_mismatch[bc]" value="1" {{ !empty($age_mismatch)? ($age_mismatch['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
					 </td>
					 <td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="age_mismatch[hm]" value="{{ (!empty($age_mismatch) && ($age_mismatch['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age_mismatch[hm]" value="0"> @endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="age_mismatch[hm]" value="1" {{ !empty($age_mismatch)? ($age_mismatch['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
					 </td>
					 <td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="age_mismatch[hc]" value="{{ (!empty($age_mismatch) && ($age_mismatch['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="age_mismatch[hc]" value="0"> @endif
					 	<input class="checkboxbig" type="checkbox" class="full-checkbox" name="age_mismatch[hc]" value="1" {{ !empty($age_mismatch)? ($age_mismatch['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
					 </td>
				   </tr>



		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">3</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Correct Customer ID is reflecting in Bajaj Claim form?</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="customer_id[bm]" value="{{ (!empty($customer_id) && ($customer_id['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="customer_id[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="customer_id[bm]" value="1" {{ !empty($customer_id)? ($customer_id['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="customer_id[bc]" value="{{ (!empty($customer_id) && ($customer_id['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="customer_id[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="customer_id[bc]" value="1" {{ !empty($customer_id)? ($customer_id['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="customer_id[hm]" value="{{ (!empty($customer_id) && ($customer_id['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="customer_id[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="customer_id[hm]" value="1" {{ !empty($customer_id)? ($customer_id['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="customer_id[hc]" value="{{ (!empty($customer_id) && ($customer_id['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="customer_id[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="customer_id[hc]" value="1" {{ !empty($customer_id)? ($customer_id['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>

		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">4</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Is Date of Death same in the following documents?<br>
		                Bajaj Claim form, Death Certificate and FIR or Post Mortem Report</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="dod[bm]" value="{{ (!empty($dod) && ($dod['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="dod[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="dod[bm]" value="1" {{ !empty($dod)? ($dod['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="dod[bc]" value="{{ (!empty($dod) && ($dod['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="dod[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="dod[bc]" value="1" {{ !empty($dod)? ($dod['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="dod[hm]" value="{{ (!empty($dod) && ($dod['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="dod[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="dod[hm]" value="1" {{ !empty($dod)? ($dod['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="dod[hc]" value="{{ (!empty($dod) && ($dod['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="dod[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="dod[hc]" value="1" {{ !empty($dod)? ($dod['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">5</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Is this Accidental / Murder Death?</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="is_mlc[bm]" value="{{ (!empty($is_mlc) && ($is_mlc['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="is_mlc[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="is_mlc[bm]" value="1" {{ !empty($is_mlc)? ($is_mlc['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="is_mlc[bc]" value="{{ (!empty($is_mlc) && ($is_mlc['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="is_mlc[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="is_mlc[bc]" value="1" {{ !empty($is_mlc)? ($is_mlc['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="is_mlc[hm]" value="{{ (!empty($is_mlc) && ($is_mlc['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="is_mlc[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="is_mlc[hm]" value="1" {{ !empty($is_mlc)? ($is_mlc['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="is_mlc[hc]" value="{{ (!empty($is_mlc) && ($is_mlc['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="is_mlc[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="is_mlc[hc]" value="1" {{ !empty($is_mlc)? ($is_mlc['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		           <tr>
		          	<td class="col-7 lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">If above is Yes, is Post Mortem or FIR Report attached?</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="fir_attached[bm]" value="{{ (!empty($fir_attached) && ($fir_attached['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="fir_attached[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="fir_attached[bm]" value="1" {{ !empty($fir_attached)? ($fir_attached['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="fir_attached[bc]" value="{{ (!empty($fir_attached) && ($fir_attached['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="fir_attached[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="fir_attached[bc]" value="1" {{ !empty($fir_attached)? ($fir_attached['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="fir_attached[hm]" value="{{ (!empty($fir_attached) && ($fir_attached['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="fir_attached[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="fir_attached[hm]" value="1" {{ !empty($fir_attached)? ($fir_attached['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="fir_attached[hc]" value="{{ (!empty($fir_attached) && ($fir_attached['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="fir_attached[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="fir_attached[hc]" value="1" {{ !empty($fir_attached)? ($fir_attached['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">6</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Check Seal & Sign on Death Certificate, FIR & Post Mortem Report<br>
			  	 			<strong>(Proceed If Death Certificate is Computer generated with barcode)</strong></span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="death_certificate[bm]" value="{{ (!empty($death_certificate) && ($death_certificate['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="death_certificate[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="death_certificate[bm]" value="1" {{ !empty($death_certificate)? ($death_certificate['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="death_certificate[bc]" value="{{ (!empty($death_certificate) && ($death_certificate['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="death_certificate[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="death_certificate[bc]" value="1" {{ !empty($death_certificate)? ($death_certificate['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="death_certificate[hm]" value="{{ (!empty($death_certificate) && ($death_certificate['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="death_certificate[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="death_certificate[hm]" value="1" {{ !empty($death_certificate)? ($death_certificate['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="death_certificate[hc]" value="{{ (!empty($death_certificate) && ($death_certificate['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="death_certificate[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="death_certificate[hc]" value="1" {{ !empty($death_certificate)? ($death_certificate['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">7</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">In death certificate, is date of registration and date of issuance are equal or greater than date of death? If no, get revised death certificate</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="valid_certificate[bm]" value="{{ (!empty($valid_certificate) && ($valid_certificate['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="valid_certificate[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="valid_certificate[bm]" value="1" {{ !empty($valid_certificate)? ($valid_certificate['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="valid_certificate[bc]" value="{{ (!empty($valid_certificate) && ($valid_certificate['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="valid_certificate[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="valid_certificate[bc]" value="1" {{ !empty($valid_certificate)? ($valid_certificate['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="valid_certificate[hm]" value="{{ (!empty($valid_certificate) && ($valid_certificate['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="valid_certificate[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="valid_certificate[hm]" value="1" {{ !empty($valid_certificate)? ($valid_certificate['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="valid_certificate[hc]" value="{{ (!empty($valid_certificate) && ($valid_certificate['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="valid_certificate[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="valid_certificate[hc]" value="1" {{ !empty($valid_certificate)? ($valid_certificate['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		          <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">8</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font"><strong>Documents attached (Mandatory)</strong><br>
			  	 			<label class="table-font">a) Bajaj Claimant Statement (Claim Form) with only nominee signature</label></span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_bajaj[bm]" value="{{ (!empty($doc_bajaj) && ($doc_bajaj['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_bajaj[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_bajaj[bm]" value="1" {{ !empty($doc_bajaj)? ($doc_bajaj['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_bajaj[bc]" value="{{ (!empty($doc_bajaj) && ($doc_bajaj['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_bajaj[bc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_bajaj[bc]" value="1" {{ !empty($doc_bajaj)? ($doc_bajaj['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_bajaj[hm]" value="{{ (!empty($doc_bajaj) && ($doc_bajaj['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_bajaj[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_bajaj[hm]" value="1" {{ !empty($doc_bajaj)? ($doc_bajaj['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_bajaj[hc]" value="{{ (!empty($doc_bajaj) && ($doc_bajaj['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_bajaj[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_bajaj[hc]" value="1" {{ !empty($doc_bajaj)? ($doc_bajaj['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">b) Death certificate issued by registrar of birth & death (Form No. 6)</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_death[bm]" value="{{ (!empty($doc_death) && ($doc_death['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_death[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_death[bm]" value="1" {{ !empty($doc_death)? ($doc_death['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_death[bc]" value="{{ (!empty($doc_death) && ($doc_death['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_death[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_death[bc]" value="1" {{ !empty($doc_death)? ($doc_death['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_death[hm]" value="{{ (!empty($doc_death) && ($doc_death['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_death[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_death[hm]" value="1" {{ !empty($doc_death)? ($doc_death['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_death[hc]" value="{{ (!empty($doc_death) && ($doc_death['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_death[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_death[hc]" value="1" {{ !empty($doc_death)? ($doc_death['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		          <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">c) Post Mortem  / FIR Report  (For accident / murder Case)</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_fir[bm]" value="{{ (!empty($doc_fir) && ($doc_fir['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_fir[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_fir[bm]" value="1" {{ !empty($doc_fir)? ($doc_fir['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_fir[bc]" value="{{ (!empty($doc_fir) && ($doc_fir['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_fir[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_fir[bc]" value="1" {{ !empty($doc_fir)? ($doc_fir['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_fir[hm]" value="{{ (!empty($doc_fir) && ($doc_fir['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_fir[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_fir[hm]" value="1" {{ !empty($doc_fir)? ($doc_fir['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_fir[hc]" value="{{ (!empty($doc_fir) && ($doc_fir['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_fir[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_fir[hc]" value="1" {{ !empty($doc_fir)? ($doc_fir['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		          <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">d) ID and Age Proof of the deceased</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_proof[bm]" value="{{ (!empty($doc_proof) && ($doc_proof['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_proof[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_proof[bm]" value="1" {{ !empty($doc_proof)? ($doc_proof['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_proof[bc]" value="{{ (!empty($doc_proof) && ($doc_proof['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_proof[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_proof[bc]" value="1" {{ !empty($doc_proof)? ($doc_proof['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_proof[hm]" value="{{ (!empty($doc_proof) && ($doc_proof['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_proof[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_proof[hm]" value="1" {{ !empty($doc_proof)? ($doc_proof['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_proof[hc]" value="{{ (!empty($doc_proof) && ($doc_proof['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_proof[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_proof[hc]" value="1" {{ !empty($doc_proof)? ($doc_proof['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65 td-bg">
			  	 		<span class="table-font">e) Original Loan Closure Request from Nominee</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_closure_request[bm]" value="{{ (!empty($doc_closure_request) && ($doc_closure_request['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_closure_request[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_closure_request[bm]" value="1" {{ !empty($doc_closure_request)? ($doc_closure_request['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_closure_request[bc]" value="{{ (!empty($doc_closure_request) && ($doc_closure_request['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_closure_request[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_closure_request[bc]" value="1" {{ !empty($doc_closure_request)? ($doc_closure_request['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_closure_request[hm]" value="{{ (!empty($doc_closure_request) && ($doc_closure_request['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_closure_request[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_closure_request[hm]" value="1" {{ !empty($doc_closure_request)? ($doc_closure_request['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_closure_request[hc]" value="{{ (!empty($doc_closure_request) && ($doc_closure_request['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_closure_request[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_closure_request[hc]" value="1" {{ !empty($doc_closure_request)? ($doc_closure_request['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
		          <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65 td-bg">
			  	 		<span class="table-font ">f) ECS/ACH Mandate</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_ecs[bm]" value="{{ (!empty($doc_ecs) && ($doc_ecs['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_ecs[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_ecs[bm]" value="1" {{ !empty($doc_ecs)? ($doc_ecs['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="doc_ecs[bc]" value="{{ (!empty($doc_ecs) && ($doc_ecs['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_ecs[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_ecs[bc]" value="1" {{ !empty($doc_ecs)? ($doc_ecs['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_ecs[hm]" value="{{ (!empty($doc_ecs) && ($doc_ecs['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_ecs[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_ecs[hm]" value="1" {{ !empty($doc_ecs)? ($doc_ecs['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="doc_ecs[hc]" value="{{ (!empty($doc_ecs) && ($doc_ecs['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="doc_ecs[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="doc_ecs[hc]" value="1" {{ !empty($doc_ecs)? ($doc_ecs['hc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
			  	 </table>

			  	 <table class="twocol-table">
			  	 	<tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-92">
			  	 		<span class="table-font "><strong>Nominee bank account details in BLOCK letters (English only)</strong></span>
			  	 	</td>
			  	 	
		          </tr>
			  	 </table>

			  	 <table class="twocol-table">
			  	 	<tr class="td-bg2">
			  	 		<td class="col-7 tdchecklist">
			  	 			<strong class="mandate-text text-rotate">MANDATORY</strong>
			  	 		</td>
			  	 		<td class="col-92">
			  	 			<table class="twocol-table-nopadding">
			  	 				<tr class="td-bg2">
			  	 				  <td class="col-40">
			  	 				  	<span class="table-font "><strong>g) Nominee name as per a/c passbook</strong></span>
			  	 				  </td>
			  	 				  <td class="col-60"><input class="fullwidth" type="text" name="nominee_name" value="{{$checklistdata->nominee_name}}"></td>
			  	 				</tr>
			  	 				<tr class="td-bg2">
			  	 				  <td class="col-40">
			  	 				  	<span class="table-font "><strong>h) Bank Account Number</strong></span>
			  	 				  </td>
			  	 				  <td class="col-60">
			  	 				  	<input class="fullwidth" type="text" name="acc_no" value="{{$checklistdata->acc_no}}">
			  	 				  </td>
			  	 				</tr>
			  	 				<tr class="td-bg2">
			  	 				  <td class="col-40">
			  	 				  	<span class="table-font "><strong>i) Name of the Bank</strong></span>
			  	 				  </td>
			  	 				  <td class="col-60"><input class="fullwidth" type="text" name="bank_name" value="{{$checklistdata->bank_name}}"></td>
			  	 				</tr>
			  	 				
			  	 			</table>

			  	 			 <table class="twocol-table">
						  	 	<tr class="td-bg2">
                                  <td>j)MICR Code</td>
                                  <td><input class="" type="text" name="micr" value="{{$checklistdata->micr}}"></td></td>	
                                   <td>k)IFSC Code</td>
                                  	<td><input class="" type="text" name="ifsc" value="{{$checklistdata->ifsc}}"></td></td>	
						  	 	</tr>
						  	 </table>

						  	 <table class="twocol-table">
						  	 	<tr class="td-bg2">
			  	 				  <td class="col-40">
			  	 				  	<span class="table-font "><strong>l) Bank Branch Name</strong></span>
			  	 				  </td>
			  	 				  <td class="col-60"><input class="fullwidth" type="text" name="branch" value="{{$checklistdata->branch}}"></td>
			  	 				</tr>
						  	 </table>
			  	 		</td>
			  	 	</tr>
			  	 	
			  	 </table>
			  	

			  	 <table class="twocol-table">
			  	 	<tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">9</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font ">Are all Documents are clear and readable?</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="docs_readable[bm]" value="{{ (!empty($docs_readable) && ($docs_readable['bm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="docs_readable[bm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="docs_readable[bm]" value="1" {{ !empty($docs_readable)? ($docs_readable['bm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id == '1100')
				 	 	<input type="hidden" name="docs_readable[bc]" value="{{ (!empty($docs_readable) && ($docs_readable['bc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="docs_readable[bc]" value="0">@endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="docs_readable[bc]" value="1" {{ !empty($docs_readable)? ($docs_readable['bc'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id == '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="docs_readable[hm]" value="{{ (!empty($docs_readable) && ($docs_readable['hm'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="docs_readable[hm]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="docs_readable[hm]" value="1" {{ !empty($docs_readable)? ($docs_readable['hm'] == '1')?'checked':'': ''}} {{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist td-bg">
					 	@if(Auth::user()->branch_id != '1100')
				 	 	<input type="hidden" name="docs_readable[hc]" value="{{ (!empty($docs_readable) && ($docs_readable['hc'] == '1')) ?'1':'0'}}">
				 	 	@else <input type="hidden" name="docs_readable[hc]" value="0"> @endif
			  	 		<input class="checkboxbig" type="checkbox" class="full-checkbox" name="docs_readable[hc]" value="1" {{ !empty($docs_readable)? ($docs_readable['hc'] == '1')?'checked':'': ''}}
			  	 		{{(Auth::user()->branch_id != '1100')?'disabled':''}}>
			  	 	</td>
		          </tr>
			  	 </table>

			  	 <div class="margintop"></div>

			  	 <label class="table-font"><strong>Declaration:</strong>  I hereby confirm that all the fields in the checklist above have been checked by me.</label>

			  	  <table class="twocol-table margintop">
			  	  <tr>
		          	<td class="col-30"><label class="table-head-font"></label></td>
			  	 	<td class="col-20"><label class="table-head-font">Employee ID</label></td>
			  	 	<td class="col-30"><label class="table-head-font">Name</label></td>
			  	 	<td class="col-10"><label class="table-head-font">Signature</label></td>
			  	 	<td class="col-10"><label class="table-head-font">Date</label></td>
		          </tr>
		          <tr>
		          	<td class="col-30"><label class="table-head-font">CCR / CRS / Cashier (Maker)</label></td>
			  	 	<td class="col-20">
			  	 		<input class="fullwidth" type="text" name="bo_maker_emp" {{(Auth::user()->branch_id != '1100')?'':'readonly'}} ></td>
			  	 	<td class="col-30">
			  	 		<input class="fullwidth" type="text" name="bo_maker_name" {{(Auth::user()->branch_id != '1100')?'':'readonly'}}>
			  	 	</td>
			  	 	<td class="col-10">
			  	 		<input class="fullwidth" type="text" name="bo_maker_sign" {{(Auth::user()->branch_id != '1100')?'':'readonly'}}>
			  	 	</td>
			  	 	<td class="col-10">
			  	 		<input class="fullwidth" type="text" name="bo_maker_date" {{(Auth::user()->branch_id != '1100')?'':'readonly'}}>
			  	 	</td>
		          </tr>
		          <tr>
		          	<td class="col-30"><label class="table-head-font">CRM / PM (Checker)</label></td>
			  	 	<td class="col-20"><input class="fullwidth" type="text" name="bo_checker_emp" {{(Auth::user()->branch_id != '1100')?'':'readonly'}}>
			  	 	</td>
			  	 	<td class="col-30">
			  	 		<input class="fullwidth" type="text" name="bo_checker_name" {{(Auth::user()->branch_id != '1100')?'':'readonly'}}>
			  	 	</td>
			  	 	<td class="col-10">
			  	 		<input class="fullwidth" type="text" name="bo_checker_sign" {{(Auth::user()->branch_id != '1100')?'':'readonly'}}>
			  	 	</td>
			  	 	<td class="col-10">
			  	 		<input class="fullwidth" type="text" name="bo_checker_date" {{(Auth::user()->branch_id != '1100')?'':'readonly'}}>
			  	 	</td>
		          </tr>
			  	 </table>

			  	 <hr/>
		         	
		         <span class="table-head-font">For HO USE ONLY</span>
			  	 <table class="twocol-table-noborder">
			  	 	<tr>
			  	 		<td>
			  	 			<table class="twocol-table">
				  	 			<tr >
				  	 				<td class="col-40"><label class="table-head-font">Insurance Maker</label></td>
				  	 				<td class="col-60"><input class="fullwidth" type="text" name="ho_maker_emp" {{(Auth::user()->branch_id == '1100')?'':'readonly'}}></td>
				  	 			</tr>
			  	 		   </table>
			  	 		</td>

			  	 		<td>
			  	 			<table class="twocol-table">
				  	 			<tr>
				  	 				<td class="col-40"><label class="table-head-font">Insurance Checker</label></td>
				  	 				<td class="col-60"><input class="fullwidth" type="text" name="ho_checker_emp" {{(Auth::user()->branch_id == '1100')?'':'readonly'}}></td>
				  	 			</tr>
			  	 		   </table>
			  	 		</td>
			  	 		
			  	 	</tr>

			  	 	
			  	 </table>
		  	 </div>
             <input type="hidden" name="claim_id" value="{{ encrypt($data->id)}}">
		  </div>
		  <div class="d-flex">
			<div class="ms-auto">
				<button class="btn btn-sm btn-danger">Submit</button>
			</div>
		</div>	
		</div>	

		
       </form>
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
</script>
@endsection