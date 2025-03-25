@extends('layouts.admin')

@section('content')
<div class="rightPanel h-100">

    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Create Role</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>



    <!-- Validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row h-100 align-items-start align-content-lg-stretch">
        <div class="col-6">
            <div class="form-card">
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf

                    <!-- Role Name -->
                    <div class="form-group mb-3">
                        <label for="name">Role Name</label>
                        <input type="text" name="name" placeholder="Type Role Name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <!-- Permissions Checkboxes -->
                    <div class="form-group">
                        <label for="permissions" class="form-label">Assign Permissions</label>
                        <div class="row">
                            @foreach ($permissions as $permission)
                                <div class="col-md-6">
                                    <input type="checkbox" name="permissions[]"  value="{{ $permission->name }}">
                                    <label class="form-label">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary mt-3">Create Role</button>
                </form>
            </div>
            <p class="small text-center opacity-50 text-muted">Dummy Content Here's a template for a Permission Form. Feel free to customize it according to your needs</p>

        </div>
    </div>


</div>
@endsection
