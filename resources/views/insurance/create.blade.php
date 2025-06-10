@extends('layouts.insurance-app')
@section('content')

<div class="container">
	<div class="py-4">
		<label class="label-font-header">Insurance Form</label>
	</div>

	<div class="py-2">
		<div class="row">
			<div class="col-3">
				<div class="card card-design tab-active">
					<div class="card-header">Head Office</div>
				</div>
			</div>

			<div class="col-3">
				<div class="card card-design">
					<div class="card-header">Branch Office</div>
				</div>
			</div>

			<div class="col-3">
				<div class="card card-design">
					<div class="card-header">Check List</div>
				</div>
			</div>
		</div>

		<div class="py-3
		" id="head_off">
		<div class="row">
			<div class="col-3 mb-3">
			    <label class="form-label">REGION</label>
			    <select class="form-control form-select">
			    	<option>Select</option>
			    	<option value="South">South</option>
			    	<option value="North">North</option>
			    	<option value="East">East</option>
			    	<option value="West">West</option>
			    	
			    	
			    </select>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">BRANCH ID-NAME</label>
			    <input type="text" class="form-control" name="branch" value="1100-Koramangala" readonly>
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Email address</label>
			    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Email address</label>
			    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
			</div>

			<div class="col-3 mb-3">
			    <label class="form-label">Email address</label>
			    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
			</div>
		</div>

		</div>


	</div>
</div>
@endsection