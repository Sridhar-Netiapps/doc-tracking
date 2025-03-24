@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Create New User</h3>
    </div>

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- User Creation Form -->
    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <!-- Name Input -->
        <div class="mb-4">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" id="name" required maxlength="255" value="{{ old('name') }}">
        </div>

        <!-- Email Input -->
        <div class="mb-4">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
        </div>

        <!-- Password Input -->
        <div class="mb-4">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" id="password" required>
        </div>

        <!-- Confirm Password Input -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm Password:</label>
            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
        </div>

    

     
        <!-- Submit and Cancel Buttons -->
        <div class="form-group d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
