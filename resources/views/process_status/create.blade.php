@extends('layouts.admin')

@section('content')
    <div class="rightPanel h-100">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Process Status</h3>
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


<div class="row h-100">
    <div class="col-6">
        <div class="form-card">
            <h2 class="mb-4">Create New Process Status</h2>
            <div class="form-fields">
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <form id="process-status" action="{{ route('process_status.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" id="name" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- <div class="row">
                        <div class="col">
                            <div class="mb-4">
                                <label for="created_by" class="form-label">Created By:</label>
                                <select name="created_by" class="form-select" required>
                                    <option value="1">Person 1</option>
                                    <option value="2">Person 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-4">
                                <label for="updated_by" class="form-label">Updated By</label>
                                <select name="updated_by" class="form-select" required>
                                    <option value="1">Person 1</option>
                                    <option value="2">Person 2</option>
                                </select>
                            </div> -->
                        </div>
                    </div>


                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary me-3">Save</button>
                        <a href="{{ route('process_status.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {   
        $("#process-status").validate({
            rules: {
                name: { required: true, sanitize: true },
            },
            messages: {
                name: { required: "Process Status is required" },
            },
            submitHandler: function(form) {
                Swal.fire({
                    title: 'Alert?',
                    text: "Are you sure you want to submit this form?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f78f35',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes!'
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
