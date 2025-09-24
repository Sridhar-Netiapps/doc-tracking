@extends('layouts.admin')

@section('content')
<div class="rightPanel h-100">

    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Edit Role: {{ $role->name }}</h3>
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


    <div class="row h-100 align-items-start align-content-lg-stretch">
        <div class="col-6">
            <div class="form-card">
    <!-- Edit Role Form -->
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Role Name -->
        <div class="form-group">
            <label for="name">Role Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control alphanumeric" value="{{ $role->name }}" required>
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
