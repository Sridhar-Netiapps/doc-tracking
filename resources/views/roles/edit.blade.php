@extends('layouts.app')

@section('content')
<div class="container-fluid pageContainer">
    <h1>Edit Role: {{ $role->name }}</h1>

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

    <!-- Edit Role Form -->
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Role Name -->
        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
        </div>

        <!-- Permissions Checkboxes -->
        <div class="form-group">
            <label for="permissions">Assign Permissions</label>
            <div class="row">
                @foreach ($permissions as $permission)
                    <div class="col-md-3">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                        <label>{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Role</button>
    </form>
</div>
@endsection
