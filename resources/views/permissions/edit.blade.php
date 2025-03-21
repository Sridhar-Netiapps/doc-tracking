@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1>Edit Permission</h1>
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

    <!-- Edit Permission Form -->
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST" id="permissionEditForm">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Permission Name</label>
            <input type="text" name="name" class="form-control" value="{{ $permission->name }}" id="name" required>
        </div>
        
        <button type="submit" class="btn btn-success">Update Permission</button>
    </form>
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
