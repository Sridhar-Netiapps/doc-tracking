@extends('layouts.admin')

@section('content')
<div class="rightPanel">

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
        <div><a href="{{ route('process_status.create') }}" class="btn btn-primary">Create New Status</a></div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Status</th>
                            {{-- <th>Created By</th> --}}
                            <!-- <th>Updated By</th> -->
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($statuses as $status)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $status->name }}</td>
                                <td>
                                    <span class="badge {{ $status->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $status->status ? __('Active') : __('Inactive') }}
                                    </span>
                                </td>
                                {{-- <td>
                                <span class="badge {{ $status->created_by ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $status->created_by }}
                                </span>
                                </td> --}}
                                <!-- <td>
                                <span class="badge {{ $status->updated_by ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $status->updated_by == 1 ? __('Person 1') : __('Person 2') }}
                                </span>
                                </td> -->
                                <td>
                                    <div class="btn-actions">
                                        <a href="{{ route('process_status.edit', $status->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        {{-- <form action="{{ route('process_status.destroy', $status->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="">
                        {{ $statuses->links('pagination::bootstrap-5') }}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
