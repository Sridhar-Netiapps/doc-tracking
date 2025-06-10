@extends('layouts.insurance-app')
@section('content')
<div class="container-fluid p-4">
	<div class="d-flex">
		<label>Claim Forms</label>
		<div class="ms-auto">
			<div class="d-flex">
				<a class="atags form-btn" href="{{route('create_insurance')}}"><button class="btn btn-secondary">Create New</button></a>
				<div class="d-flex">
					<form method="GET" action="">
	                 <div class="input-group mb-3">
	                  <input class="form-control" type="text" name="search" placeholder="Search By Name" >
	                  <div class="input-group-prepend">
	                     <button class="btn btn-dark rounded-0" type="submit" >Search</button>
	                  </div>
	                 </div>
	               </form>
				</div>
			</div>
			
		</div>
	</div>

	<div class="py-4">
		<table class="table table-resnponsive table-bordered">
			<thead>
				<th>Creation Date</th>
				<th>Region</th>
				<th>Branch</th>
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
				<tr>
					
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endsection	