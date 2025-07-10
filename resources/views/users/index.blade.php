@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">User List</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>
        </div>
        @if(auth()->user()->can('create-user'))
        <div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
        </div>
        @endif
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
                                <th>Employee ID</th>
                                <th>Region</th>
                                <th>Branch Code</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Status</th>
                                <th>Mobile Number</th>
                                <th>Date of Joining</th>
                                @role('master')
                                <th>Roles</th>
                                {{-- <th>Permissions</th> --}}
                                @endrole
                                @canany(['edit-user','delete-user'])
                                <th>Action</th>
                                @endcanany
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }}</td>
                                    <td>{{ $user->employee_id }}</td>
                                    <td>{{ $user->region }}</td>
                                    <td>{{ $user->branch_id }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ ucfirst($user->gender) }}</td>
                                    <td>{{ $user->dob }}</td>
                                    <td>{{ ucfirst($user->status) }}</td>
                                    <td>{{ $user->mobile_number }}</td>
                                    <td>{{ $user->doj }}</td>
                                    @role('master')
                                    <td>
                                        @foreach ($user->roles as $role)
                                            <span class="badge text-bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    {{-- <td>
                                        @foreach ($user->getAllPermissions() as $permission)
                                            <span class="badge text-bg-secondary">{{ $permission->name }}</span>
                                        @endforeach
                                    </td> --}}
                                    @endrole
                                    @canany(['edit-user','delete-user'])
                                    <td>
                                        <div class="btn-actions">
                                            @can('edit-user')
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                                            @endcan
                                            {{-- @can('delete-user')
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                            </form>
                                            @endcan --}}
                                        </div>
                                    </td>
                                    @endcanany
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class=""">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
