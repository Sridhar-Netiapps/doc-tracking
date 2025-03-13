@extends('layouts.app')
@section('content')
<link href="{{ asset('css/styledoc.css') }}" rel="stylesheet">
<div class="container mt-5">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        Process Status List
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-card">
        <!-- Add New Status Button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Statuses</h4>
            <a href="{{ route('process_status.create') }}" class="btn ujjivan-green">Create New Status</a>
        </div>

        <!-- Status Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statuses as $status)
                        <tr>
                            <td>{{ $status->id }}</td>
                            <td>{{ $status->name }}</td>
                            <td>
                                <span class="badge {{ $status->status ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $status->status ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $status->created_by ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $status->created_by == 1 ? __('Person 1') : __('Person 2') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $status->updated_by ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $status->updated_by == 1 ? __('Person 1') : __('Person 2') }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-actions">
                                    <a href="{{ route('process_status.edit', $status->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                    <form action="{{ route('process_status.destroy', $status->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
