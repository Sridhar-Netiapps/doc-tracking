@extends('layouts.insurance-app')
@section('content')
<div class="container-fluid p-4">
	<div class="d-flex">
		<strong>Insurance Leads</strong>
		<div class="ms-auto">
			<div class="d-flex">

			@if(auth::user()->branch_id == '1100')
				<a data-bs-toggle="modal" data-bs-target="#importModal"  class="nav-link form-btn"  ><button class="btn btn-secondary btn-text p-2" id="btn_open_import_module">Import</button></a>

				<a class="nav-link form-btn" href="{{route('create_insurance')}}" ><button class="btn btn-success btn-text p-2" id="btn_create_lead">Create Lead</button></a>

			@endif

				<div class="d-flex">
					<form method="GET" action="{{ route('insurance_list')}}">
	                 <div class="input-group mb-3">
	                  <input class="form-control clsAlphaNoOnly" type="text" name="search" placeholder="Search" value="{{$search}}">
	                  <button class="btn btn-secondary">GO</button>	                 
	                 </div>
	               </form>
	               <a class="nav-link" href="{{route('insurance_list')}}"><i class="fa fa-sync m-3"></i></a>
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
                    <button class="btn btn-danger" id="btn_import">Import</button>
                    
                    <a class="ms-auto nav-link" target="_blank" href="{{ URL::to('/')}}/template/ClaimLeadDetailsTemplate.xlsx" id="btn_download_template"><span class="btn btn-outline-secondary">Download Template</span></a>
                    </div>
                    
                </form>

                 
              </div>
              
            </div>
          </div>
        </div>
<!-- Modal -->

       @if(Session::has('message'))
		 <script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
		  var mesage = '{{ session('message') }}';
		  Swal.fire({
		        title: 'Import Result',
		        text: mesage,
		        icon: 'success',  
		        confirmButtonText: 'OK'
		        }).then((result) => {
	            if (result.isConfirmed) {
	                // 👇 Redirect to another URL
	               // window.location.href = "{{ url('/insurance/claim_forms') }}";
	            }
		    });
		 </script>
		 
		@endif

		@if(session()->has('failures'))
		    <div class="alert alert-danger bg-import-error">
		    	<strong class="text-danger">{{ session()->get('message') }}</strong><br>
		        <table class="table table-bordered ">
		        	<tr>
		        		<th>Row Number</th>
		        		<th>Error Description</th>
		        	</tr>
		        
                  <tbody>
		            @foreach(session()->get('failures') as $failure)  
		                <tr>
		                	<td>{{ $failure->row() +1 }}</td>
		                	<td>{{ implode(', ', $failure->errors()) }}</td>
		                </tr>
		            @endforeach
		          </tbody>
		          </table>
		    </div>

		    @if(!empty(session()->get('errordata')))
			    <form action="{{ route('download.error.report') }}" method="POST">
			        @csrf
			        <input type="hidden" name="data" value="{{ base64_encode(json_encode(session()->get('errordata'))) }}">
			        <button type="submit" class="btn btn-danger">Download Error Report Data</button>
			    </form>
			@endif


		@endif



	<div class="py-4">
		<table class="table table-resnponsive table-bordered table-striped">
			<thead class="table-dark">
				<th class="text-table-head">Intimation Date</th>
				<th class="text-table-head">Lead ID</th>
				<th class="text-table-head">Product</th>
				<th class="text-table-head">Actual ID</th>
				<th class="text-table-head">Deceased Name</th>
				<th class="text-table-head">Deceased Type</th>
				<th class="text-table-head">Loan Acc No</th>
				<th class="text-table-head">Claim Status</th>
				<th class="text-table-head">Amount</th>
				<th class="text-table-head">Policy Covered</th>
				<th class="text-table-head">Document Received </th>
				<th class="text-table-head">Submision to Partner</th>
				<th class="text-table-head">Action</th>
			</thead>

			<tbody>
				@foreach($data as $key=>$value)
				<tr>
					<td>{{ ($value->intimation_date !='')?date('d M,Y',strtotime($value->intimation_date)):''}}</td>
					<td class="text-table">{{ $value->utrn}}</td>
					<td class="text-table">{{ $value->product}}</td>
					<td class="text-table">{{ $value->actual_id}}</td>
					<td class="text-table">{{ $value->deceased_name}}</td>
					<td class="text-table">{{ $value->deceased}}</td>
					<td class="text-table">{{ $value->load_acc_id}}</td>
					<td class="text-table">{{ $value->cliam_status}}</td>
					<td class="number">{{ $value->claim_amount}}</td>
					<td class="text-table">{{ ($value->policy_covered_date !='')?date('d M,Y',strtotime($value->policy_covered_date)):''}}</td>
					<td class="text-table">{{  ($value->doc_rec_date !='')?date('d M,Y',strtotime($value->doc_rec_date)):''}}</td>
					<td class="text-table">{{  ($value->submit_to_partner_date !='')?date('d M,Y',strtotime($value->submit_to_partner_date)):''}}</td>
					<td>
						<div class="d-flex">
							<a class="nav-link" href="{{ route('view_claim_details',encrypt($value->id))}}" ><button class="btn btn-sm btn-warning me-2" id="btn_view">View</button></a>
							@if($value->cliam_status !='Completed')
							<a class="nav-link" href="{{ route('edit_claim_details',[$landingTab,encrypt($value->id)])}}" ><button class="btn btn-sm btn-danger" id="btn_edit">Edit</button></a>
							@endif
						</div>
					</td>
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