@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vendors</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div>
            <a href="{{ route('vendor.create') }}" class="btn btn-primary">Add Vendor</a>
        </div>
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
                        <th>Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if(count($vendors) > 0)
                    @foreach ($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->name }}</td>
                            <td>{{ $vendor->location }}</td>
                            <td>
                                <a href="{{ route('vendor.edit', $vendor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" >Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No Vendors Found...</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="">
            {{ $vendors->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
