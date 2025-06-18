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
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                           
                        </div>
                    </div>
                    <button class="btn btn-danger">Import</button>
                    
                </form>

                 
              </div>
              
            </div>
          </div>
        </div>
<!-- Modal -->


	<div class="py-4">
		<table class="table table-resnponsive table-bordered table-striped">
			<thead class="table-dark">
				<th>Unique Reference</th>
				<th>Creation Date</th>
				<th>Region</th>
				<th>Branch</th>
				<th>Partner</th>
				<th>Product</th>
				<th>Policy Number</th>
				<th>Loan Acc ID</th>
				<th>Customer ID</th>
				<th>Deceased</th>
				<th>Cause of Death</th>
				<th>Claim Status</th>
				<th>PDC/RL Status</th>
				<th>Action</th>
			</thead>

			<tbody>
				@foreach($data as $key=>$value)
				<tr>
					<td>{{ $value->utrn}}</td>
					<td>{{ date('d M,Y',strtotime($value->created_at))}}</td>
					<td>{{ $value->region}}</td>
					<td>{{ $value->branch}}</td>
					<td>{{ $value->partners->partner}}</td>
					<td>{{ $value->products->product}}</td>
					<td>{{ $value->policy_number}}</td>
					<td>{{ $value->load_acc_id}}</td>
					<td>{{ $value->cust_id}}</td>
					<td>{{ $value->deceased}}</td>
					<td>{{ $value->deathCause->cause}}</td>
					<td>{{ $value->claim_stat->claim_status}}</td>
					<td>{{ $value->rlStatus->rl_status}}</td>
					<td><a class="nav-link" href="{{ route('view_claim_details',encrypt($value->id))}}"><button class="btn btn-sm btn-outline-secondary">View</button></a></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection	