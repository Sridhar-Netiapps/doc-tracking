@extends('layouts.admin')

@section('content')
    <div class="rightPanel h-100">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Process Status</h3>
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


<div class="row h-100">
    <div class="col-6">
        <div class="form-card">
            <h2 class="mb-4">Create New Process Status</h2>
            <div class="form-fields">
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('process_status.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="name" required pattern="^[a-zA-Z\s]+$" maxlength="55" value="{{ old('name') }}">
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-4">
                                <label for="created_by" class="form-label">Created By:</label>
                                <select name="created_by" class="form-select" required>
                                    <option value="1">Person 1</option>
                                    <option value="2">Person 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-4">
                                <label for="updated_by" class="form-label">Updated By</label>
                                <select name="updated_by" class="form-select" required>
                                    <option value="1">Person 1</option>
                                    <option value="2">Person 2</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary me-3">Save</button>
                        <a href="{{ route('process_status.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

        </div>
    </div>


</div>
@endsection
