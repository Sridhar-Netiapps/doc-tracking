@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Role</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div>
            <a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="form-card">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->name === 'super_admin' ? 'id_maintenance' : $role->name }}</td>
                                <td>
                                    <a href="{{ route('roles.edit', Crypt::encryptString($role->id)) }}" class="btn btn-warning btn-sm">Edit</a>
                                    {{-- <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="">
        {{ $roles->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
