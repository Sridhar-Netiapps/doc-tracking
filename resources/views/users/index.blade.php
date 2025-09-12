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
                                    <td></td>
                                    @role('master|super_admin|admin')
                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span>{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        {{-- <td>
                                            @foreach ($user->getAllPermissions() as $permission)
                                                <span class="badge text-bg-secondary">{{ $permission->name }}</span>
                                            @endforeach
                                        </td> --}}
                                    @endrole
                                    @role('master|super_admin')
                                        <td>
                                            <div class="btn-actions">
                                                @can('edit-user')
                                                {{-- <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a> --}}
                                                <a href="{{ route('users.edit', Crypt::encryptString($user->id)) }}" class="btn btn-warning btn-sm">Edit</a>
                                                @endcan
                                                {{-- @can('delete-user')
                                                <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                                </form>
                                                @endcan --}}
                                            </div>
                                        </td>
                                    @endrole
                                </tr>
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
<div class="modal fade" id="add-user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
            {{-- <form id="rma-upload" action="{{ route('users.create') }}" method="GET">
                @csrf
                <div class="modal-header text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Upload Vendor Movement Information</h5>
                </div>
                <div class="modal-body">
                    <label for="excel_file" class="form-label">Upload File</label>  <a href="{{ route('vendor.sample.download') }}" class="btn btn-link"> Download Sample File </a>
                    <input type="file" name="excel_file" class="form-control file-validate"  data-ext="csv,xls,xlsx" required> 
                    <label class="text-danger mt-3" id="excel_file-error"></label>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form> --}}
            <form id="rma-upload" action="{{ route('users.get') }}" method="POST">
                @csrf
                <div class="modal-header text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Get User Information</h5>
                </div>
                <div class="modal-body">
                {{-- <div class="col-6 row form-card"> --}}
                    <input type="text" name="id" class="form-control alphanumeric" placeholder="Enter Employee ID" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg get-user"><strong>Get User Details</strong></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {   
        $("#users").validate({
            rules: {
                first_name: { required: true, sanitize: true },
                last_name: { required: true, sanitize: true },
                email: { required: true, sanitize: true },
                employee_id: { required: true, sanitize: true },
                region: { required: true, sanitize: true },
                mobile_number: { 
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10,
                    sanitize: true
                }
            },
            messages: {
                first_name: { required: "First name is required" },
                last_name: { required: "Last name is required" },
                email: { required: "email is required" },
                employee_id: { required: "Employee ID is required" },
                region: { required: "region is required" },
                mobile_number: { 
            required: "Mobile number is required",
            pattern: "Mobile number must be exactly 10 digits"
            }
            },
            submitHandler: function(form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to submit this form?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f78f35',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, submit it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
                // event.preventDefault();
                // $('#confirmModal').modal('show');
                // $('button.yes').on('click', function() {
                //     form.submit();
                // });
                // $('button.no').on('click', function() {
                //     $('#confirmModal').modal('hide');
                // });
            }
        });

        $('.get-user').click(function () {
            $('#add-user').modal('show');
            // const id = $('input[name="emp-id"]').val();
            // let url = "{{ url('users/create') }}/" + id;
            // $.get(url);
        });
    });
</script>

@endsection
