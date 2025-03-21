@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Edit User</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" id="name" required maxlength="255" value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-4">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" id="email" required value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-4">
            <label for="roles" class="form-label">Roles:</label>
            <select name="roles[]" class="form-control" id="roles" multiple required>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="permissions" class="form-label">Permissions:</label>
            <select name="permissions[]" class="form-control" id="permissions" multiple required>
                @foreach ($permissions as $permission)
                    <option value="{{ $permission->name }}" {{ $user->hasPermissionTo($permission->name) ? 'selected' : '' }}>
                        {{ $permission->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
