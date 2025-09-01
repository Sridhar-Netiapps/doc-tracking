@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Courier List</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        </div>
        @role('super_admin|master')
        <div><a href="{{ route('couriers.create') }}" class="btn btn-primary">Add New Courier</a></div>
        @endrole
    </div>{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Courier List</h2>
    <a href="{{ route('couriers.create') }}" class="btn btn-primary mb-3">Add Courier</a> --}}

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                        <tr>
                        <th>ID</th>
                        <th>Courier ID</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Status</th>
                        @role('admin|super_admin|master')
                        <th>Actions</th>
                        @endrole
                    </tr>
        @foreach($couriers as $courier)
        <tr>
            <td>{{ $courier->id }}</td>
            <td>{{ $courier->courier_id }}</td>
            <td>{{ $courier->name }}</td>
            <td>{{ $courier->number }}</td>
            <td>{{ $courier->status }}</td>
            @role('admin|super_admin|master')
            <td>
                <a href="{{ route('couriers.edit', Crypt::encryptString($courier->id)) }}" class="btn btn-sm btn-warning">Edit</a>
                {{-- <form action="{{ route('couriers.destroy', $courier->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this courier?')">Delete</button>
                </form> --}}
            </td>
            @endrole
        </tr>
        @endforeach
    </table>
</div>
@endsection
