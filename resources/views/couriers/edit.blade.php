@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Edit Courier </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">

    <form action="{{ route('couriers.update', $courier->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Courier ID</label>
            <input type="text" name="courier_id" class="form-control" value="{{ $courier->courier_id }}" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $courier->name }}" required>
        </div>
        <div class="mb-3">
            <label>Number</label>
            <input type="text" name="number" class="form-control" value="{{ $courier->number }}" required>
        </div>
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" required>{{ $courier->address }}</textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active" {{ $courier->status == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ $courier->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('couriers.index') }}" class="  btn btn-secondary ms-3">Cancel</a>
    </form>
</div>
@endsection
