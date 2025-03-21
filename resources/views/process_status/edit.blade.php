@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Edit Process Status</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('process_status.update', $status->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" class="form-control" id="name" required pattern="^[a-zA-Z\s]+$" maxlength="55" value="{{ old('name', $status->name) }}">
        </div>

        <div class="mb-4">
            <label for="status" class="form-label">Status:</label>
            <select name="status" class="form-control" required>
                <option value="1" {{ $status->status == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ $status->status == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="updated_by" class="form-label">Updated By:</label>
            <select name="updated_by" class="form-control" required>
                <option value="1" {{ $status->updated_by == 1 ? 'selected' : '' }}>Person 1</option>
                <option value="2" {{ $status->updated_by == 2 ? 'selected' : '' }}>Person 2</option>
            </select>
        </div>

            <div class="d-flex ">
            <!-- Save Button -->
            <button type="submit" class="btn btn-primary">Save</button>

            <!-- Cancel Button to Redirect to Process Status Index -->
            <a href="{{ route('process_status.index') }}" class="btn btn-secondary ml-2">Cancel</a>
        </div>

    </form>
</div>
@endsection
