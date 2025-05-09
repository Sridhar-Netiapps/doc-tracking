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
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        </div>
        <div><a href="{{ route('couriers.create') }}" class="btn btn-primary">Create New Department</a></div>
    </div>{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Courier List</h2>
    <a href="{{ route('couriers.create') }}" class="btn btn-primary mb-3">Add Courier</a> --}}

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Courier ID</th>
            <th>Name</th>
            <th>Number</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        @foreach($couriers as $courier)
        <tr>
            <td>{{ $courier->id }}</td>
            <td>{{ $courier->courier_id }}</td>
            <td>{{ $courier->name }}</td>
            <td>{{ $courier->number }}</td>
            <td>{{ $courier->status }}</td>
            <td>
                <a href="{{ route('couriers.edit', $courier->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('couriers.destroy', $courier->id) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this courier?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
