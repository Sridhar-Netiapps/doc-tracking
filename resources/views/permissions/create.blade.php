@extends('layouts.admin')

@section('content')
<div class="rightPanel h-100">

    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Create New Permission</h3>
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
    <div class="row">
        <div class="col-6">
            <div class="form-card">
                <form action="{{ route('permissions.store') }}" method="POST" id="permissionCreateForm">
                    @csrf
                    <label class="form-label">Create Permission</label>
                    <div class="input-group mb-3">
                        <input type="text" name="name" placeholder="Type Permission Name" class="form-control" id="name" value="{{ old('name') }}" required>
                        <button class="btn btn-primary" type="submit" id="button-addon1">Create Permission</button>
                    </div>

                </form>
            </div>
            <p class="small text-center opacity-50 text-muted">Dummy Content Here's a template for a Permission Form. Feel free to customize it according to your needs</p>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        // Form Validation Setup
        $('#permissionCreateForm').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                name: {
                    required: "Please enter the permission name.",
                    minlength: "Permission name must be at least 3 characters long."
                }
            },
            submitHandler: function (form) {
                form.submit();
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    });
</script>
@endsection
