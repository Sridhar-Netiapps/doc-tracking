@extends('layouts.app')

@section('content')
<div class="container-fluid pageContainer">
    <h1>Create Role</h1>

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

    <!-- Create Role Form -->
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <!-- Role Name -->
        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <!-- Permissions Checkboxes -->
        <div class="form-group">
            <label for="permissions">Assign Permissions</label>
            <div class="row">
                @foreach ($permissions as $permission)
                    <div class="col-md-3">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}">
                        <label>{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Role</button>
    </form>
</div>
@endsection
