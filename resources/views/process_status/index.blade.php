@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h3>Process Status</h3>
        <a href="{{ route('process_status.create') }}" class="btn btn-primary">Create New Status</a>
    </div>


    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif


    <div class="row">
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