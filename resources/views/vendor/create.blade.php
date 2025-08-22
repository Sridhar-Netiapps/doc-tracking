@extends('layouts.admin')

@section('content')
<div class="rightPanel h-100">

    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Create Vendor</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="row h-100">
        <div class="col-6">
            <div class="form-card">
    <form id="vendor" action="{{ route('vendor.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" placeholder="Enter vendor name" value="{{ old('name') }}" required>
        </div>
    
        <div class="mb-3">
            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
            <input type="text" name="location" class="form-control" placeholder="Enter location" value="{{ old('location') }}" required>
        </div>
    
        <div class="d-flex">
            <button type="submit" class="btn btn-primary me-3">Save</button>
            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</div>
</div>
</div>
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {   
        $("#vendor").validate({
                rules: {
                name: { required: true, sanitize: true },
                location: { required: true, sanitize: true }
            },
            messages: {
                name: { required: "Vendor Name is required" },
                location: { required: "Location is required" }
            }

            submitHandler: function(form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to submit this form?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f78f35',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endsection
