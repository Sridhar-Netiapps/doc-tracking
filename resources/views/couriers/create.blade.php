@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Create New Courier</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">

    <form action="{{ route('couriers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Courier ID</label>
            <input type="text" name="courier_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Number</label>
            <input type="text" name="number" class="form-control" required 
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   maxlength="10" >
        </div>               
        
        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('couriers.index') }}" class="  btn btn-secondary ms-3">Cancel</a>
    </form>
</div>
@endsection
