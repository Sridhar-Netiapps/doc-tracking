@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Edit Process Status</h3>
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




    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <div class="form-card">
                <form action="{{ route('process_status.update', $status->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control alphanumeric" id="name" required pattern="^[a-zA-Z\s]+$" maxlength="55" value="{{ old('name', $status->name) }}">
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-4">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="1" {{ $status->status == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $status->status == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-6">
                            <div class="mb-4">
                                <label for="updated_by" class="form-label">Updated By</label>
                                <select name="updated_by" class="form-select" required>
                                    <option value="1" {{ $status->updated_by == 1 ? 'selected' : '' }}>Person 1</option>
                                    <option value="2" {{ $status->updated_by == 2 ? 'selected' : '' }}>Person 2</option>
                                </select>
                            </div>
                        </div> -->

                    </div>





                    <div class="d-flex ">
                        <!-- Save Button -->
                        <button type="submit" class="btn btn-primary">Save</button>
                        <!-- Cancel Button to Redirect to Process Status Index -->
                        <a href="{{ route('process_status.index') }}" class="btn btn-secondary ms-3">Cancel</a>
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
