@extends('layouts.admin')

@section('content')
    <div class="rightPanel h-100">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Departments</h3>
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
    <div class="col-12">
        <div class="form-card">
            <h2 class="mb-4">Create New Department</h2>
            <div class="form-fields">
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <form id="departments" action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}" method="POST">
                    @csrf
                    @if(isset($department))
                        @method('PUT')
                    @endif
            
                    <div class="row">
                        <div class="col-4 mb-4">
                            <label for="name">Department Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $department->name ?? '') }}" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
            
                        <div class="col-4 mb-4">
                            <label for="slug">Short Name</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $department->slug ?? '') }}" required>
                            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
            
                    <button type="submit" class="btn btn-primary ">{{ isset($department) ? 'Update' : 'Save' }}</button>
                    <a href="{{ route('departments.index') }}" class="  btn btn-secondary">Cancel</a>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $("#departments").on("submit", function () {
            $(".text-danger").html(""); // Clear previous errors
        });
    
        $("#departments").validate({
            rules: {
                name: { required: true },
                slug: { required: true },
            },
            messages: {
                name: { required: "Department name is required" },
                slug: { required: "Department slug is required" },
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                // event.preventDefault();
                // $('#confirmModal').modal('show');
                // $('button.yes').on('click', function() {
                //     form.submit();
                // });
                // $('button.no').on('click', function() {
                //     $('#myModal').modal('hide');
                // });
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
