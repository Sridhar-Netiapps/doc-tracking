@extends('layouts.insurance-app')
@section('content')
<div class="container-fluid p-4">
	<div class="d-flex">
		<strong>Insurance Leads</strong>
		<div class="ms-auto">
			<div class="d-flex">

			@if(auth::user()->branch_id == '1100')
				<a data-bs-toggle="modal" data-bs-target="#importModal"  class="nav-link form-btn" ><button class="btn btn-secondary btn-text p-2">Import</button></a>

				<a class="nav-link form-btn" href="{{route('create_insurance')}}"><button class="btn btn-success btn-text p-2">Create Lead</button></a>

			@endif

				<div class="d-flex">
					<form method="GET" action="{{ route('insurance_list')}}">
	                 <div class="input-group mb-3">
	                  <input class="form-control" type="text" name="search" placeholder="Search" value="{{$search}}">	                 
	                 </div>
	               </form>
				</div>
			</div>
			
		</div>
	</div>

	<!-- Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Insurance Lead details from Excel sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form action="{{ route('import_claim_data') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <div class="custom-file text-left">
                            <input type="file" name="file" accept=".xlsx" class="custom-file-input" id="customFile" required>
                           
                        </div>
                    </div>
                    <div class="d-flex">
                    <button class="btn btn-danger">Import</button>
                    
                    <a class="ms-auto nav-link" target="_blank" href="{{ URL::to('/')}}/template/ClaimLeadDetailsTemplate.xlsx"><span class="btn btn-outline-secondary">Download Template</span></a>
                    </div>
                    
                </form>

                 
              </div>
              
            </div>
          </div>
        </div>
<!-- Modal -->

       @if(Session::has('message'))
		 <script type="text/javascript" nonce="wUDPhZ1Z60inspnMCukimCi">
		  var mesage = '{{ session('message') }}';
		  Swal.fire({
		        title: 'Import Result',
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


	<div class="py-4">
		<table class="table table-resnponsive table-bordered table-striped">
			<thead class="table-dark">
				<th>Lead ID</th>
				<th>Product</th>
				<th>CIF ID</th>
				<th>Deceased Name</th>
				<th>Deceased Type</th>
				<th>Loan Acc No</th>
				<th>Claim Status</th>
				<th>Amount</th>
				<th>Policy Covered</th>
				<th>Date of Document Received</th>
				<th>Date of Submision to Partner</th>
				<th>Action</th>
			</thead>

			<tbody>
				@foreach($data as $key=>$value)
				<tr>
					<td>{{ $value->utrn}}</td>
					<td>{{ $value->product}}</td>
					<td>{{ $value->cust_id}}</td>
					<td>{{ $value->deceased_name}}</td>
					<td>{{ $value->deceased}}</td>
					<td>{{ $value->load_acc_id}}</td>
					<td>{{ $value->cliam_status}}</td>
					<td class="number">{{ $value->claim_amount}}</td>
					<td>{{ ($value->policy_covered_date !='')?date('d M,Y',strtotime($value->policy_covered_date)):''}}</td>
					<td>{{  ($value->doc_rec_date !='')?date('d M,Y',strtotime($value->doc_rec_date)):''}}</td>
					<td>{{  ($value->submit_to_partner_date !='')?date('d M,Y',strtotime($value->submit_to_partner_date)):''}}</td>
					<td><a class="nav-link" href="{{ route('view_claim_details',encrypt($value->id))}}"><button class="btn btn-sm btn-outline-secondary">View</button></a></td>
				</tr>
				@endforeach
			</tbody>
		</table>

		 <label>Showing {{ $data->firstItem() }} to {{ $data->lastItem() }}
                of {{$data->total()}} results</label>

              {!! $data->appends('abc')->links('pagination::bootstrap-4') !!}
    	
	</div>
</div>
@endsection	