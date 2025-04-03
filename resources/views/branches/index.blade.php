@extends('layouts.admin')

@section('content')
<div class="rightPanel">

    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Branches List</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        </div>
        <div><a href="{{ route('branches.create') }}" class="btn btn-primary">Create New Branch</a></div>
    </div>




    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Branch Name</th>
                            <th>Branch Code</th>
                            <th>Region Name</th>
                            <th>Business Type</th>
                            <th>RBI Classification</th>
                            <th>Branch Office Type</th>
                            <th>Pincode</th>
                            <th>City</th>
                            <th>Status</th>
                            {{-- <th>Created By</th> --}}
                            {{-- <th>Updated By</th> --}}
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($branches as $branch)
                            <tr>
                                <td>{{ $branch->id }}</td>
                                <td>{{ $branch->name }}</td>
                                <td>{{ $branch->code }}</td>
                                <td>{{ $branch->region_name }}</td>
                                <td>{{ ucwords(str_replace("_"," ",$branch->business_type)) }}</td>
                                <td>{{ ucwords(str_replace("-"," ",$branch->rbi_classification)) }}</td>
                                <td>{{ $branch->branch_office_type }}</td>
                                <td>{{ $branch->pincode }}</td>
                                <td>{{ $branch->city }}</td>
                                <td>
                                    <span class="badge {{ $branch->status ? 'bg-success' : 'bg-secondary' }}">{{ $branch->status ? __('Active') : __('Inactive') }}</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <a href="{{ route('branches.edit', $branch->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" class="d-inline">
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
