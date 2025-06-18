@extends('layouts.insurance-app')
@section('content')

<div class="container">
	<div class="py-4">
		<label class="label-font-header">Create New Lead</label>
	</div>

	<div class="py-2">
		<div class="row">
			<!-- <div class="col-3">
				<button class="form-control btn btn-sm btn-secondary btn-toggle p-2 active card-design"  value="ho">Head Office </button>
			</div> -->

			<!-- <div class="col-3">
				<button class="form-control btn-secondary btn btn-sm btn-toggle p-2 card-design"  value="bo">Branch Office </button>
			</div> -->

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
		<div class="row">
			<div class="col-3 mb-3">
			    <label class="form-label">Region</label>
			    <select class="form-control form-select" name="region"  >
			    	<option value="">Select</option>
			    	<option {{(old('region') == 'South')?'selected':''}} value="South" >South</option>
			    	<option {{(old('region') == 'North')?'selected':''}} value="North">North</option>
			    	<option {{(old('region') == 'East')?'selected':''}} value="East">East</option>
			    	<option {{(old('region') == 'West')?'selected':''}} value="West">West</option>	
			    </select>
			    @error('region')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Branch ID-Name</label>
			    <input type="text" class="form-control" name="branch" value="1100-Koramangala" >
			    @error('branch')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Partner</label>
			    <select class="form-control form-select" name="partner"  >
			    	<option value="">Select</option>
			    	@foreach($partners as $key=>$value)
			    	   <option {{(old('partner') == $value->id)?'selected':''}} value="{{$value->id}}">{{$value->partner}}</option>
			    	@endforeach
			    </select>
			    @error('partner')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Product</label>
			    <select class="form-control form-select" name="product"  >
			    	<option value="">Select</option>
			    	@foreach($products as $key=>$value)
			    	   <option {{(old('product') == $value->id)?'selected':''}} value="{{$value->id}}">{{$value->product}}</option>
			    	@endforeach
			    </select>
			    @error('product')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Policy Number</label>
			    <input type="text" class="form-control numberonly" name="policy_number" value="{{ old('policy_number')}}">
			    @error('policy_number')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Customer ID</label>
			    <input type="text" class="form-control numberonly" name="cust_id" value="{{ old('cust_id')}}">
			    @error('cust_id')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">ACTUAL ID</label>
			    <input type="text" class="form-control numberonly" name="actual_id" value="{{ old('actual_id')}}">
			    @error('actual_id')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Deceased Name</label>
			    <input type="text" class="form-control numberonly" name="deceased_name" value="{{ old('deceased_name')}}">
			    @error('deceased_name')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">MP NO</label>
			    <input type="text" class="form-control numberonly" name="mp_no" value="{{ old('mp_no')}}">
			    @error('mp_no')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Policy Covered</label>
			    <input type="date" class="form-control" name="policy_covered_date" value="{{ old('policy_covered_date')}}">
			    @error('policy_covered_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Loan Tenure</label>
			    <input type="text" class="form-control numberonly" name="loan_tenure" value="{{ old('loan_tenure')}}">
			    @error('loan_tenure')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Policy Expired Date</label>
			    <input type="date" class="form-control" name="policy_expiry_date" value="{{ old('policy_expiry_date')}}">
			    @error('policy_expiry_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of Death</label>
			    <input type="date" class="form-control" name="date_of_death" value="{{ old('date_of_death')}}">
			    @error('date_of_death')<div class="text-error">{{ $message }}</div>@enderror
			</div>


			<div class="col-3 mb-3">
			    <label class="form-label">Gender</label>
			    <select class="form-control form-select" name="gender">
			    	<option value="">Select</option>
			    	<option {{ ( old('gender')=='Male')?'selected':''}}  value="Male">Male</option>
			    	<option {{ ( old('gender')=='Female')?'selected':''}}  value="Female">Female</option>
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Deceased</label>
			    <select class="form-control form-select" name="deceased" >
			    	<option value="">Select</option>
			    	@foreach($deceased as $key=>$value)
			    	   <option {{ ( old('deceased')==$value)?'selected':''}} 
			    	    value="{{$value}}">{{$value}}</option>
			    	@endforeach
			    </select>
			    @error('deceased')<div class="text-error">{{ $message }}</div>@enderror

			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date Of Death Intimation</label>
			    <input type="date" class="form-control" name="intimation_date" value="{{ old('intimation_date')}}">
			    @error('intimation_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Age</label>
			    <input type="text" class="form-control numberonly" name="age" value="{{ old('age')}}">
			    @error('age')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Place of Death</label>
			    <select class="form-control form-select" name="place_of_death">
			    	<option>Select</option>
			    	@foreach($placeofdeath as $key=>$value)
			    	   <option {{ ( old('place_of_death')==$value->id)?'selected':''}}  value="{{$value->id}}">{{$value->place}}</option>
			    	@endforeach
			    </select>
			    @error('place_of_death')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Cause of Death</label>
			    <select class="form-control form-select" name="cause_of_death" >
			    	<option>Select</option>
			    	@foreach($deathcause as $key=>$value)
			    	   <option {{ ( old('cause_of_death')==$value->id)?'selected':''}} value="{{$value->id}}">{{$value->cause}}</option>
			    	@endforeach
			    </select>
			    @error('cause_of_death')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Loan Account ID</label>
			    <input type="text" class="form-control numberonly" name="load_acc_id" value="{{ old('load_acc_id')}}">
			    @error('load_acc_id')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Claim Amount</label>
			    <input type="text" class="form-control numberonly" name="claim_amount" value="{{ old('claim_amount')}}">
			    @error('claim_amount')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of Birth</label>
			    <input type="date" class="form-control" name="dob" value="{{ old('dob')}}">
			    @error('dob')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Claim Status</label>
			    <select class="form-control form-select" name="cliam_status" >
			    	<option value="">Select</option>
			    	@foreach($claimstatus as $key=>$value)
			    	   <option {{ ( old('cliam_status')==$value->id)?'selected':''}} value="{{$value->id}}">{{$value->claim_status}}</option>
			    	@endforeach
			    </select>
			    @error('cliam_status')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">CAS Status</label>
			    <select class="form-control form-select" name="cas_status">
			    	<option value="">Select</option>
			    	<option {{ ( old('cas_status')=='CAS Process')?'selected':''}} value="CAS Process">CAS Process</option>
			    	<option {{ ( old('cas_status')=='PDC Process')?'selected':''}} value="PDC Process">PDC Process</option>
			    </select>
			    @error('cas_status')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Nominee Name</label>
			    <input type="text" class="form-control" name="nominee_name" value="{{ old('nominee_name')}}">
			    @error('nominee_name')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Relationship</label>
			    <select class="form-control form-select" name="relationship" >
			    	<option value="">Select</option>
			    	@foreach($relationship as $key=>$value)
			    	   <option {{ ( old('relationship')==$value->id)?'selected':''}} value="{{$value->id}}">{{$value->relationship}}</option>
			    	@endforeach
			    </select>
			    @error('relationship')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Nominee Contact No</label>
			    <input type="date" class="form-control numberonly" name="nominee_number" value="{{ old('nominee_number')}}">
			    @error('nominee_number')<div class="text-error">{{ $message }}</div>@enderror
			</div>

		</div>

		<div class="row">
			<div class="col-3 mb-3">
			    <label class="form-label">Loan Outstanding Amt</label>
			    <input type="text" class="form-control numberonly" name="loan_outstanding" value="{{ old('loan_outstanding')}}">
			    @error('loan_outstanding')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Payable to Nominee</label>
			    <input type="text" class="form-control numberonly" name="payable_to_nominee" value="{{ old('payable_to_nominee')}}">
			    @error('payable_to_nominee')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Acknowledgement Received Date</label>
			    <input type="date" class="form-control numberonly" name="ack_rec_date" value="{{ old('ack_rec_date')}}">
			    @error('ack_rec_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Packet Number</label>
			    <input type="text" class="form-control numberonly" name="pkt_no" value="{{ old('pkt_no')}}">
			    @error('pkt_no')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">SPDC/RL Status</label>
			    <select class="form-control form-select" name="rl_status" >
			    	<option value="">Select</option>
			    	@foreach($rlStat as $stat)
			    	  <option {{ (old('rl_status')==$stat->id)?'selected':'' }} value="{{$stat->id}}">{{$stat->rl_status}}</option>
                    @endforeach
                    @error('rl_status')<div class="text-error">{{ $message }}</div>@enderror
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Processed By</label>
			    <select class="form-control form-select" name="processed_by">
			    	<option value="">Select</option>
			    	@foreach($procesedby as $proc)
			    	  <option {{ (old('processed_by')==$proc)?'selected':'' }} value="{{$proc}}">{{$proc}}</option>
                    @endforeach
                    @error('processed_by')<div class="text-error">{{ $message }}</div>@enderror
			    </select>
			</div>
			<div class="col-3 mb-3"></div>
			<div class="col-3 mb-3"></div>

			<div class="col-6 mb-3">
			    <label class="form-label">HO Remarks</label>
			    <textarea class="form-control" name="ho_remark">{{ old('ho_remarks')}}</textarea>
			    @error('ho_remark')<div class="text-error">{{ $message }}</div>@enderror
			</div>
			<div class="col-6 mb-3"></div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of document received</label>
			    <input type="date" class="form-control" name="doc_rec_date" value="{{ old('doc_rec_date')}}">
			    @error('doc_rec_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of submision to partner</label>
			    <input type="date" class="form-control" name="submit_to_partner_date" value="{{ old('submit_to_partner_date')}}">
			    @error('submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-6 mb-3">
			    <label class="form-label">Remarks</label>
			    <input type="text" class="form-control numberonly" name="ho_remark2" value="{{ old('ho_remark2')}}">
			    @error('ho_remark2')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of re-submision to partner</label>
			    <input type="date" class="form-control" name="re_submit_to_partner_date" value="{{ old('re_submit_to_partner_date')}}">
			    @error('re_submit_to_partner_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of settlement</label>
			    <input type="date" class="form-control" name="settlement_date" value="{{ old('settlement_date')}}">
			    @error('settlement_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">NEFT Rejection Date</label>
			    <input type="date" class="form-control" name="neft_rejection_date" value="{{ old('neft_rejection_date')}}">
			    @error('neft_rejection_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">NEFT Reason For Rejection</label>
			    <input type="text" class="form-control" name="neft_rejection_reason" value="{{ old('neft_rejection_reason')}}">
			    @error('neft_rejection_reason')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Final Settlement Date</label>
			    <input type="date" class="form-control" name="final_settlement_date" value="{{ old('final_settlement_date')}}">
			    @error('final_settlement_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Recovery Status</label>
			    <input type="date" class="form-control numberonly" name="recovery_status" value="{{ old('recovery_status')}}">
			    @error('recovery_status')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Bounced CHQ No</label>
			    <input type="text" class="form-control" name="bounced_chq_no" value="{{ old('bounced_chq_no')}}">
			    @error('bounced_chq_no')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">CHQ Bounced Date</label>
			    <input type="date" class="form-control" name="bounced_chq_date" value="{{ old('bounced_chq_date')}}">
			    @error('bounced_chq_date')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">CHQ Bounced reason</label>
			    <input type="text" class="form-control" name="bounced_chq_reason" value="{{ old('bounced_chq_reason')}}">
			    @error('bounced_chq_reason')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Write off received</label>
			    <input type="text" class="form-control" name="write_off_rec" value="{{ old('write_off_rec')}}">
			    @error('write_off_rec')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Write off status</label>
			    <input type="text" class="form-control" name="write_off_status" value="{{ old('write_off_status')}}">
			    @error('write_off_status')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Handed over to Business Head</label>
			    <input type="text" class="form-control" name="handed_to_bh" value="{{ old('handed_to_bh')}}">
			    @error('handed_to_bh')<div class="text-error">{{ $message }}</div>@enderror
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Handed over to credit</label>
			    <input type="text" class="form-control" name="handed_to_credit" value="{{ old('handed_to_credit')}}">
			    @error('handed_to_credit')<div class="text-error">{{ $message }}</div>@enderror
			</div>
		</div>

		<div class="d-flex">
			<div class="ms-auto">
				<button type="submit" class="btn btn-sm btn-success btn-text p-2">Submit</button>
			</div>
	    </div>

        </form>
        
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

</script>

<script>
document.getElementById('myForm').addEventListener('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault();

        const firstInvalid = this.querySelector(':invalid');
        if (firstInvalid) {
            // Scroll manually using offset if fixed headers are present
            const offset = -100; // adjust based on header height
            const y = firstInvalid.getBoundingClientRect().top + window.scrollY + offset;

            window.scrollTo({ top: y, behavior: 'smooth' });

            // Delay to allow scroll before showing message
            setTimeout(() => {
                firstInvalid.focus();
                firstInvalid.reportValidity(); // forces the message
            }, 400);
        }
    }
});

</script>


@endsection