@extends('layouts.admin')

@section('content')
    <div class="rightPanel h-100">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Branches</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        </div>
    </div>


<div class="row h-100">
    <div class="col-12">
        <div class="form-card">
            <h2 class="mb-4">Create New Branch</h2>
            <div class="form-fields">
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <form id="branch" action="{{ isset($branch) ? route('branches.update', $branch->id) : route('branches.store') }}" method="POST">
                    @csrf
                    @if(isset($branch))
                        @method('PUT')
                    @endif
            
                    <div class="row">
                        <div class="col-4 mb-4">
                            <label for="name">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $branch->name ?? '') }}" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
            
                        <div class="col-4 mb-4">
                            <label for="code">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $branch->code ?? '') }}" required>
                            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-4 mb-4">
                            <label for="region_id">Region ID <span class="text-danger">*</span></label>
                            <input type="text" name="region_id" id="region_id" class="form-control" value="{{ old('region_id', $branch->region_id ?? '') }}" required>
                            @error('region_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
            
                        <div class="col-4 mb-4">
                            <label for="region_name">Region Name</label>
                            <input type="text" name="region_name" id="region_name" class="form-control" value="{{ old('region_name', $branch->region_name ?? '') }}">
                            @error('region_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-4 mb-4">
                            <label for="business_type">Business Type <span class="text-danger">*</span></label>
                            <input type="text" name="business_type" id="business_type" class="form-control" value="{{ old('business_type', $branch->business_type ?? '') }}" required>
                            @error('business_type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
            
                        <div class="col-4 mb-4">
                            <label for="rbi_classification">RBI Classification <span class="text-danger">*</span></label>
                            <input type="text" name="rbi_classification" id="rbi_classification" class="form-control" value="{{ old('rbi_classification', $branch->rbi_classification ?? '') }}" required>
                            @error('rbi_classification') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary ">{{ isset($branch) ? 'Update' : 'Save' }}</button>
                    <a href="{{ route('branches.index') }}" class="  btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#branch").on("submit", function () {
            $(".text-danger").html(""); // Clear previous errors
        });
    
        $("#branch").validate({
            rules: {
                name: { required: true, sanitize: true },
                code: { required: true, digits: true, sanitize: true },
                region_id: { required: true, digits: true, sanitize: true },
                business_type: { required: true, sanitize: true },
                rbi_classification: { required: true, sanitize: true },
            },
            messages: {
                name: { required: "Branch name is required" },
                code: { required: "Branch code is required", digits: "Only numbers are allowed" },
                region_id: { required: "Region ID is required", digits: "Only numbers are allowed" },
                business_type: { required: "Business type is required" },
                rbi_classification: { required: "RBI Classification is required" },
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            }
        });
    });
</script>
@endsection
