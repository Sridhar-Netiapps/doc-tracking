@extends('layouts.admin')

@section('content')
<div class="rightPanel">

    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Edit Permission</h3>
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

    <!-- Validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-6">
            <div class="form-card">
                <form action="{{ route('permissions.update', $permission->id) }}" method="POST" id="permissionEditForm">
                    @csrf
                    @method('PUT')
                    <div class="input-group">

                    <label for="name" class="form-label">Permission Name</label>
                        <div class="input-group mb-3">
                            <input type="text" name="name" class="form-control" value="{{ $permission->name }}" id="name" required>
                            <button type="submit" class="btn btn-primary">Update Permission</button>
                        </div>
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
        $('#permissionEditForm').validate({
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
