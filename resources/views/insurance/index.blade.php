@extends('layouts.insurance-app')
@section('content')
<div class="container-fluid p-4">
	<div class="d-flex">
		<strong>Insurance Claim Forms</strong>
		<div class="ms-auto">
			<div class="d-flex">
				<a class="nav-link form-btn" href="{{route('create_insurance')}}"><button class="btn btn-success btn-text p-2">Create New</button></a>
				<div class="d-flex">
					<form method="GET" action="">
	                 <div class="input-group mb-3">
	                  <input class="form-control" type="text" name="search" placeholder="Search" >	                 
	                 </div>
	               </form>
				</div>
			</div>
			
		</div>
	</div>

	<div class="py-4">
		<table class="table table-resnponsive table-bordered table-striped">
			<thead class="table-dark">
				<th>Creation Date</th>
				<th>Region</th>
				<th>Branch</th>
				<th>Partner</th>
				<th>Product</th>
				<th>Policy Number</th>
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
					<td>{{ date('d M,Y',strtotime($value->created_at))}}</td>
					<td>{{ $value->region}}</td>
					<td>{{ $value->branch}}</td>
					<td>{{ $value->partners->partner}}</td>
					<td>{{ $value->products->product}}</td>
					<td>{{ $value->policy_number}}</td>
					<td>{{ $value->cust_id}}</td>
					<td>{{ $value->deceased}}</td>
					<td>{{ $value->deathCause->cause}}</td>
					<td>{{ $value->claim_stat->claim_status}}</td>
					<td>{{ $value->rl_status}}</td>
					<td><a class="nav-link" href="{{ route('view_claim_details',encrypt($value->id))}}"><button class="btn btn-sm btn-outline-secondary">View</button></a></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection	