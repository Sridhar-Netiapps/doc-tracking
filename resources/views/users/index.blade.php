@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">User List</h3>
                <button class="btn btn-sm btn-primary me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Filters</button>
                @if(Request::segment(2) == 'filter')
                <form method="GET" action="{{ route('user.export') }}">
                    @foreach(($filters ?? []) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <button type="submit" class="btn btn-sm btn-success">
                        Export
                    </button>
                </form>
                @endif
            </div>
        </div>
        @role('super_admin|master')
        <div>
            {{-- <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a> --}}
            <button class="btn btn-primary get-user">Create User</a>
        </div>
        @endrole
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
                                <th>Designation</th>
                                @role('master|super_admin|admin')
                                <th>Roles</th>
                                {{-- <th>Permissions</th> --}}
                                @endrole
                                @role('master|super_admin')
                                <th>Action</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                @if (!$user->roles->contains('name', 'master'))
                                    <tr>
                                        <td>{{ $loop->iteration + ($users->perPage() * ($users->currentPage() - 1)) }}</td>
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
                                        <td>{{ $user->current_designation }}</td>
                                        
                                        @role('master|super_admin|admin')
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    <span>
                                                        {{-- {{ $role->name }} --}}
                                                        {{ $role->name === 'super_admin' ? 'id_maintenance' : $role->name }}
                                                    </span>
                                                @endforeach
                                            </td>
                                        @endrole
                            
                                        @role('master|super_admin')
                                            <td>
                                                <div class="btn-actions">
                                                    @can('edit-user')
                                                        <a href="{{ route('users.edit', Crypt::encryptString($user->id)) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        @endrole
                                    </tr>
                                @endif
                            @endforeach
                        
                        </tbody>
                    </table>
                    <div class="">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <h5>Filters</h5>
        <form method="POST" action="{{ route('activity.filter') }}">
            @csrf
            <div class="row">
                <input type="hidden" value="1" name="user"/>
                <div class="col-12 mt-3">
                    <select class="form-select region" name="region">
                        <option value="">Select Region</option>
                        <option value="South" {{ ($filters['region'] ?? '') == 'South' ? 'selected' : '' }}>South</option>
                        <option value="North" {{ ($filters['region'] ?? '') == 'North' ? 'selected' : '' }}>North</option>
                        <option value="East" {{ ($filters['region'] ?? '') == 'East' ? 'selected' : '' }}>East</option>
                        <option value="West" {{ ($filters['region'] ?? '') == 'West' ? 'selected' : '' }}>West</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <input type="number" class="form-control branch_id alphanumeric" placeholder="Branch Code" value="{{ old('branch_id', $filters['branch_id'] ?? '') }}" name="branch_id" min="0">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control employee_id alphanumeric" placeholder="Employee ID" value="{{ old('employee_id', $filters['employee_id'] ?? '') }}" name="employee_id">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control email" placeholder="Email ID" value="{{ old('email', $filters['email'] ?? '') }}" name="email">
                </div>
                <div class="col-12 mt-3">
                    <select class="form-select status" name="status">
                        <option value="">Select Status</option>
                        <option value="Active" {{ ($filters['status'] ?? '') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ ($filters['status'] ?? '') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Clear</a> 
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="add-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="rma-upload" action="{{ route('users.get') }}" method="POST">
                @csrf
                <div class="modal-header text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Get User Information</h5>
                </div>
                <div class="modal-body">
                    <input type="text" name="id" class="form-control alphanumeric capsonly" placeholder="Enter Employee ID" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg get-user"><strong>Get User Details</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {   
        $('.get-user').click(function () {
            $('#add-user').modal('show');
        });
    });
</script>

@endsection
