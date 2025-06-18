@extends('layouts.insurance-app')
@section('content')

<div class="container">
	<div class="d-flex py-4">
		<label class="label-font-header">Update Insurance Form - {{$data->utrn}}</label>

        
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
			    <label class="form-label">Nominee Name</label>
			    <input type="text" class="form-control" name="nominee_name" value="{{ $data->nominee_name}}">
			    @error('nominee_name')<div class="text-error">{{ $message }}</div>@enderror
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
			    <label class="form-label">Nominee Contact No</label>
			    <input type="date" class="form-control numberonly" name="nominee_number" value="{{ $data->nominee_number}}">
			    @error('nominee_number')<div class="text-error">{{ $message }}</div>@enderror
			</div>

		</div>

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
			    <label class="form-label">Acknowledgement Received Date</label>
			    <input type="date" class="form-control numberonly" name="ack_rec_date" value="{{ $data->ack_rec_date}}">
			    @error('ack_rec_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Packet Number</label>
			    <input type="text" class="form-control numberonly" name="pkt_no" value="{{ $data->pkt_no}}">
			    @error('pkt_no')<div class="text-error">{{ $message }}</div>@enderror
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
			<div class="col-3 mb-3"></div>
			<div class="col-3 mb-3"></div>

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

			<div class="col-3 mb-3">
			    <label class="form-label">Recovery Status</label>
			    <input type="date" class="form-control numberonly" name="recovery_status" value="{{ $data->recovery_status}}">
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

		
        </fieldset>

        @if(auth::user()->branch_id == '1100')
		<div class="d-flex">
			<div class="ms-auto">
				<button type="submit" class="btn btn-sm btn-danger btn-text p-2">Update</button>
			</div>
	    </div>
	    @endif

        </form>
        
		</div>
        

       
     <!-- BO -->

       
		<div class="py-3 d-none" id="branch_off">
			<form method="POST" action="">
			<fieldset {{ (auth::user()->branch_id == '1100')?'disabled':''}}>
			<div class="row">
				<div class="col-3 mb-3">
				    <label class="form-label">Nominee Name as per Bank Records</label>
				    <input type="text" class="form-control numberonly" name="nominee_name_bank">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Name of the Bank</label>
				    <input type="text" class="form-control" name="bank_name">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Bank A/c Number</label>
				    <input type="text" class="form-control numberonly" name="acc_number">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">IFSC Code</label>
				    <input type="text" class="form-control numberonly" name="ifsc">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Bank Branch Name</label>
				    <input type="text" class="form-control" name="branch_name">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">SPDC-Bank Name</label>
				    <input type="text" class="form-control" name="spdc_bank_name">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">SPDC-Chq Number</label>
				    <input type="text" class="form-control numberonly" name="spdc_chk_no">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Courier Name</label>
				    <input type="text" class="form-control" name="courier_name">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">POD Number</label>
				    <input type="text" class="form-control" name="pod_no">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Cheque Sent Date</label>
				    <input type="date" class="form-control" name="cheq_sent_date">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Remarks</label>
				    <input type="text" class="form-control" name="bo_remarks">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Maker at Branch</label>
				    <input type="text" class="form-control" name="bo_maker">
				</div>

				<div class="col-3 mb-3">
				    <label class="form-label">Checker at Branch</label>
				    <input type="text" class="form-control" name="bo_checker">
				</div>

			</div>
            
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

       <form method="POST" action="">
		<div class="py-3 d-none" id="checklist">
		
			<div class="pagelayout">
		  	 <div class="contentmain">
		  	 	 <h2 class="headertext">Insurance Claim Document Checklist</h2>

		  	 	 <table class="twocol-table-noborder">
			  	 	<tr>
			  	 		<td class="col-70"><label class="label-font-bold">Branch ID & Name:  </label> <input type="text" name="" value="{{$data->branch}}" readonly> </td>
			  	 		<td class="col-30"><label class="label-font-bold"></label>
			  	 			<label class="label-font-bold">Sent Date:</label>
						      <input type="text" class="date-decoration" maxlength="2" size="2" name="dd" placeholder="DD"/>
						      <strong>/</strong>
						      <input type="text" class="date-decoration" maxlength="2" size="2" name="mm" placeholder="MM"/>
						      <strong>/</strong>
						      <input type="text" class="year-decoration" maxlength="4" size="4" name="yyyy" placeholder="YYYY" />
			  	 		</td>
			  	 	</tr>
			  	 </table>
		         
			  	 <table class="twocol-table-noborder">
			  	 	<tr>
			  	 		<td class="col-70"><label class="label-font-bold">Customer ID: <input type="text" name="" value="{{$data->cust_id}}" readonly ></label> </td>
			  	 		<td class="col-30"><label class="label-font-bold">Deceased Name:</label>
			  	 			<input type="text" name="" value="{{$data->deceased_name}}" readonly>
			  	 		</td>
			  	 	</tr>
			  	 </table>

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
				 	 <td class="col-7 tdchecklist tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				 	 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				 	 <td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				 	 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				   </tr>

				   <tr>
					 <td class="col-65">
					   <span class="table-font"><strong>If name is not matching,</strong> Need Court Affidavit (mentioning all the names)</span>
					 </td>
					 <td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
					 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
					 <td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
					 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				   </tr>

				    <tr>
				 	 <td class="col-7 tdchecklist lableslno" rowspan="2" ><label class="table-font">2</label></td>
				 	 <td class="col-65">
				    <span class="table-font">Deceased age (as per Bajaj Claim form) match with Death Certificate, Age / ID Proof, FIR
		                 or Post Mortem Report</span>
				 	 </td>
				 	 <td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				 	 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				 	 <td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				 	 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				   </tr>

				   <tr>
					 <td class="col-65">
					   <span class="table-font"><strong>If Age difference is 5 years (+ or -)</strong> Need Court Affidavit </span>
					 </td>
					 <td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
					 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
					 <td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
					 <td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
				   </tr>



		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">3</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Correct Customer ID is reflecting in Bajaj Claim form?</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>

		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">4</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Is Date of Death same in the following documents?<br>
		                Bajaj Claim form, Death Certificate and FIR or Post Mortem Report</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">5</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Is this Accidental / Murder Death?</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">If above is Yes, is Post Mortem or FIR Report attached?</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">6</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">Check Seal & Sign on Death Certificate, FIR & Post Mortem Report<br>
			  	 			<strong>(Proceed If Death Certificate is Computer generated with barcode)</strong></span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">7</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">In death certificate, is date of registration and date of issuance are equal or greater than date of death? If no, get revised death certificate</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"><label class="table-font">8</label></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font"><strong>Documents attached (Mandatory)</strong><br>
			  	 			<label class="table-font">a) Bajaj Claimant Statement (Claim Form) with only nominee signature</label></span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">b) Death certificate issued by registrar of birth & death (Form No. 6)</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">c) Post Mortem  / FIR Report  (For accident / murder Case)</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65">
			  	 		<span class="table-font">d) ID and Age Proof of the deceased</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		           <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65 td-bg">
			  	 		<span class="table-font">e) Original Loan Closure Request from Nominee</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
		          </tr>
		          <tr>
		          	<td class="col-7 tdchecklist lableslno"></td>
			  	 	<td class="col-65 td-bg">
			  	 		<span class="table-font ">f) ECS/ACH Mandate</span>
			  	 	</td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
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
			  	 				  <td class="col-60"><input class="fullwidth" type="text" name=""></td>
			  	 				</tr>
			  	 				<tr class="td-bg2">
			  	 				  <td class="col-40">
			  	 				  	<span class="table-font "><strong>h) Bank Account Number</strong></span>
			  	 				  </td>
			  	 				  <td class="col-60">
			  	 				  	<input class="fullwidth" type="text" name="">
			  	 				  </td>
			  	 				</tr>
			  	 				<tr class="td-bg2">
			  	 				  <td class="col-40">
			  	 				  	<span class="table-font "><strong>i) Name of the Bank</strong></span>
			  	 				  </td>
			  	 				  <td class="col-60"><input class="fullwidth" type="text" name=""></td>
			  	 				</tr>
			  	 				
			  	 			</table>

			  	 			 <table class="twocol-table">
						  	 	<tr class="td-bg2">
                                  <td>j)MICR Code</td>
                                  <td><input class="" type="text" name=""></td></td>	
                                   <td>k)IFSC Code</td>
                                  	<td><input class="" type="text" name=""></td></td>	
						  	 	</tr>
						  	 </table>

						  	 <table class="twocol-table">
						  	 	<tr class="td-bg2">
			  	 				  <td class="col-40">
			  	 				  	<span class="table-font "><strong>l) Bank Branch Name</strong></span>
			  	 				  </td>
			  	 				  <td class="col-60"><input class="fullwidth" type="text" name=""></td>
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
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
			  	 	<td class="col-7 tdchecklist td-bg"><input class="checkboxbig" type="checkbox" class="full-checkbox" name=""></td>
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
			  	 	<td class="col-20"><label class="table-head-font">UJJ</label></td>
			  	 	<td class="col-30"><input class="fullwidth" type="text" name=""></td>
			  	 	<td class="col-10"><input class="fullwidth" type="text" name=""></td>
			  	 	<td class="col-10"><input class="fullwidth" type="text" name=""></td>
		          </tr>
		          <tr>
		          	<td class="col-30"><label class="table-head-font">CRM / PM (Checker)</label></td>
			  	 	<td class="col-20"><label class="table-head-font">UJJ</label></td>
			  	 	<td class="col-30"><input class="fullwidth" type="text" name=""></label></td>
			  	 	<td class="col-10"><input class="fullwidth" type="text" name=""></label></td>
			  	 	<td class="col-10"><input class="fullwidth" type="text" name=""></label></td>
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
				  	 				<td class="col-60"><input class="fullwidth" type="text" name=""></td>
				  	 			</tr>
			  	 		   </table>
			  	 		</td>

			  	 		<td>
			  	 			<table class="twocol-table">
				  	 			<tr>
				  	 				<td class="col-40"><label class="table-head-font">Insurance Checker</label></td>
				  	 				<td class="col-60"><input class="fullwidth" type="text" name=""></td>
				  	 			</tr>
			  	 		   </table>
			  	 		</td>
			  	 		
			  	 	</tr>

			  	 	
			  	 </table>
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