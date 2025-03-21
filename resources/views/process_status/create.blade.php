@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Create Process Status</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('process_status.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" id="name" required pattern="^[a-zA-Z\s]+$" maxlength="55" value="{{ old('name') }}">
        </div>

        <div class="mb-4">
            <label for="status" class="form-label">Status:</label>
            <select name="status" class="form-control" required>
                <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="created_by" class="form-label">Created By:</label>
            <select name="created_by" class="form-control" required>
                <option value="1">Person 1</option>
                <option value="2">Person 2</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="updated_by" class="form-label">Updated By:</label>
            <select name="updated_by" class="form-control" required>
                <option value="1">Person 1</option>
                <option value="2">Person 2</option>
            </select>
        </div>
        <div class="d-flex">
            <button type="submit" class="btn btn-primary">Save</button>

            <!-- Cancel Button to Redirect to Process Status Index -->
            <a href="{{ route('process_status.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
