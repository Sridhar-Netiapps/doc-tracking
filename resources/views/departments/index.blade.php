@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Department List</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        </div>
        <div><a href="{{ route('departments.create') }}" class="btn btn-primary">Create New Department</a></div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                        <tr>
                            <!-- <th>S.No</th> -->
                            <th>Department Name</th>
                            <th>Short Name</th>
                            {{-- <th>Region Name</th>
                            <th>Business Type</th>
                            <th>RBI Classification</th>
                            <th>Department Office Type</th>
                            <th>Pincode</th>
                            <th>City</th> --}}
                            <th>Status</th>
                            {{-- <th>Created By</th> --}}
                            {{-- <th>Updated By</th> --}}
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($departments as $department)
                            <tr>
                                <!-- <td>{{ $loop->iteration }}</td> -->
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->slug }}</td>
                                {{-- <td>{{ $department->region_name }}</td>
                                <td>{{ ucwords(str_replace("_"," ",$department->business_type)) }}</td>
                                <td>{{ ucwords(str_replace("-"," ",$department->rbi_classification)) }}</td>
                                <td>{{ $department->department_office_type }}</td>
                                <td>{{ $department->pincode }}</td>
                                <td>{{ $department->city }}</td> --}}
                                <td>
                                    <span class="badge {{ $department->status ? 'bg-success' : 'bg-secondary' }}">{{ $department->status ? __('Active') : __('Inactive') }}</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="d-inline">
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
    </div>
</div>
@endsection
