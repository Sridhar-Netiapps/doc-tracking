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
	    <form method="POST" action="{{route('save_claim_details')}}">
	    @csrf		
		<div class="row">
			<div class="col-3 mb-3">
			    <label class="form-label">Region</label>
			    <select class="form-control form-select" name="region" required>
			    	<option value="">Select</option>
			    	<option value="South" selected >South</option>
			    	<option value="North">North</option>
			    	<option value="East">East</option>
			    	<option value="West">West</option>	
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Branch ID-Name</label>
			    <input type="text" class="form-control" name="branch" value="1100-Koramangala" readonly>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Partner</label>
			    <select class="form-control form-select" name="partner" required="required" >
			    	<option value="">Select</option>
			    	@foreach($partners as $key=>$value)
			    	   <option value="{{$value->id}}">{{$value->partner}}</option>
			    	@endforeach
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Product</label>
			    <select class="form-control form-select" name="product" required >
			    	<option value="">Select</option>
			    	@foreach($products as $key=>$value)
			    	   <option value="{{$value->id}}">{{$value->product}}</option>
			    	@endforeach
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Policy Number</label>
			    <input type="text" class="form-control numberonly" name="policy_number" required>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Customer ID</label>
			    <input type="text" class="form-control numberonly" name="cust_id" required>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">ACTUAL ID</label>
			    <input type="text" class="form-control numberonly" name="actual_id" required>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Deceased Name</label>
			    <input type="text" class="form-control numberonly" name="deceased_name">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">MP NO</label>
			    <input type="text" class="form-control numberonly" name="mp_no">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Policy Covered</label>
			    <input type="date" class="form-control" name="policy_covered_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Loan Tenure</label>
			    <input type="text" class="form-control numberonly" name="loan_tenure">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Policy Expired Date</label>
			    <input type="date" class="form-control" name="policy_expiry_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of Death</label>
			    <input type="date" class="form-control" name="date_of_death">
			</div>


			<div class="col-3 mb-3">
			    <label class="form-label">Gender</label>
			    <select class="form-control form-select" name="gender">
			    	<option value="">Select</option>
			    	<option value="Male">Male</option>
			    	<option value="Female">Female</option>
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Deceased</label>
			    <select class="form-control form-select" name="deceased" required>
			    	<option value="">Select</option>
			    	<option value="CO-APPLICANT">CO-APPLICANT</option>
			    	<option value="SPOUSE">SPOUSE</option>
			    	<option value="CUSTOMER">CUSTOMER</option>
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date Of Death Intimation</label>
			    <input type="date" class="form-control" name="intimation_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Age</label>
			    <input type="text" class="form-control numberonly" name="age">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Place of Death</label>
			    <select class="form-control form-select" name="place_of_death">
			    	<option>Select</option>
			    	@foreach($placeofdeath as $key=>$value)
			    	   <option value="{{$value->id}}">{{$value->place}}</option>
			    	@endforeach
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Cause of Death</label>
			    <select class="form-control form-select" name="cause_of_death" required="">
			    	<option>Select</option>
			    	@foreach($deathcause as $key=>$value)
			    	   <option value="{{$value->id}}">{{$value->cause}}</option>
			    	@endforeach
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Loan Account ID</label>
			    <input type="text" class="form-control numberonly" name="load_acc_id">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Claim Amount</label>
			    <input type="text" class="form-control numberonly" name="claim_amount">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of Birth</label>
			    <input type="date" class="form-control" name="dob">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Claim Status</label>
			    <select class="form-control form-select" name="cliam_status" required>
			    	<option value="">Select</option>
			    	@foreach($claimstatus as $key=>$value)
			    	   <option value="{{$value->id}}">{{$value->claim_status}}</option>
			    	@endforeach
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">CAS Status</label>
			    <select class="form-control form-select" name="cas_status">
			    	<option value="">Select</option>
			    	<option value="CAS Process">CAS Process</option>
			    	<option value="PDC Process">PDC Process</option>
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Nominee Name</label>
			    <input type="text" class="form-control" name="nominee_name">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Relationship</label>
			    <select class="form-control form-select" name="relationship" >
			    	<option value="">Select</option>
			    	@foreach($relationship as $key=>$value)
			    	   <option value="{{$value->id}}">{{$value->relationship}}</option>
			    	@endforeach
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Nominee Contact No</label>
			    <input type="date" class="form-control numberonly" name="nominee_number">
			</div>

		</div>

		<div class="row">
			<div class="col-3 mb-3">
			    <label class="form-label">Loan Outstanding Amt</label>
			    <input type="text" class="form-control numberonly" name="loan_outstanding">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Payable to Nominee</label>
			    <input type="text" class="form-control numberonly" name="payable_to_nominee">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Acknowledgement Received Date</label>
			    <input type="date" class="form-control numberonly" name="ack_rec_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Packet Number</label>
			    <input type="text" class="form-control numberonly" name="pkt_no">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">SPDC/RL Status</label>
			    <select class="form-control form-select" name="rl_status" required>
			    	<option value="">Select</option>
			    	<option value="NA">NA</option>
			    	<option value="PDC">PDC</option>
			    	<option value="PDC Process">PDC Process</option>
			    	<option value="RL Process">RL Process</option>

			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Processed By</label>
			    <select class="form-control form-select" name="processed_by">
			    	<option value="">Select</option>
			    	<option value="NA">NA</option>
			    	<option value="Vindhya">Vindhya</option>
			    	<option value="Ujjivan">Ujjivan</option>
			    	<option value="HO">HO</option>

			    </select>
			</div>
			<div class="col-3 mb-3"></div>
			<div class="col-3 mb-3"></div>

			<div class="col-6 mb-3">
			    <label class="form-label">HO Remarks</label>
			    <textarea class="form-control" name="ho_remark"></textarea>
			</div>
			<div class="col-6 mb-3"></div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of document received</label>
			    <input type="date" class="form-control" name="doc_rec_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of submision to partner</label>
			    <input type="date" class="form-control" name="submit_to_partner_date">
			</div>

			<div class="col-6 mb-3">
			    <label class="form-label">Remarks</label>
			    <input type="text" class="form-control numberonly" name="ho_remark2">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of re-submision to partner</label>
			    <input type="date" class="form-control" name="re_submit_to_partner_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Date of settlement</label>
			    <input type="date" class="form-control" name="settlement_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">NEFT Rejection Date</label>
			    <input type="date" class="form-control" name="neft_rejection_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">NEFT Reason For Rejection</label>
			    <input type="text" class="form-control" name="neft_rejection_reason">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Final Settlement Date</label>
			    <input type="date" class="form-control" name="final_settlement_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Recovery Status</label>
			    <input type="date" class="form-control numberonly" name="recovery_status">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Bounced CHQ No</label>
			    <input type="text" class="form-control" name="bounced_chq_no">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">CHQ Bounced Date</label>
			    <input type="date" class="form-control" name="bounced_chq_date">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">CHQ Bounced reason</label>
			    <input type="text" class="form-control" name="bounced_chq_reason">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Write off received</label>
			    <input type="text" class="form-control" name="write_off_rec">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Write off status</label>
			    <input type="text" class="form-control" name="write_off_status">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Handed over to Business Head</label>
			    <input type="text" class="form-control" name="handed_to_bh">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Handed over to credit</label>
			    <input type="text" class="form-control" name="handed_to_credit">
			</div>
		</div>

		<div class="d-flex">
			<div class="ms-auto">
				<button type="submit" class="btn btn-sm btn-success btn-text p-2">Submit</button>
			</div>
	    </div>

        </form>
        
		</div>
        

       
     <!-- BO -->

       
		<div class="py-3 d-none" id="branch_off">
			<form method="POST" action="">
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

			<div class="d-flex">
				<div class="ms-auto">
					<button type="submit" class="btn btn-sm btn-success btn-text p-2">Submit</button>
				</div>
		    </div>
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
			  	 		<td class="col-70"><label class="label-font-bold">Branch ID & Name:  </label> <input type="text" name=""> </td>
			  	 		<td class="col-30"><label class="label-font-bold">Sent Date:</label>
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
			  	 		<td class="col-70"><label class="label-font-bold">Customer ID: <input type="text" name=""></label> </td>
			  	 		<td class="col-30"><label class="label-font-bold">Deceased Name:</label>
			  	 			<input type="text" name="">
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
</script>
@endsection