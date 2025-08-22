@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Create New User</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/users">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="h-100 align-items-start align-content-lg-stretch">
        <form id="users" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row form-card">
                <div class="form-group col-4 mb-4">
                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="region">Region <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="region" name="region" required>
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="branch_id">Branch Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" required>
                    @error('branch_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="email">Email Id <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="designation">Designation</label>
                    <input type="text" class="form-control" id="designation" name="designation" >
                </div>
 
                <div class="form-group col-4 mb-4">
                    <label for="designation_id">Designation ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="designation_id" name="designation_id" required>
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="doj">Date of Joining <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="doj" name="doj" required>
                </div>

                <div class="form-group col-4 mb-4">
                    <label for="dor">Date of Releaving</label>
                    <input type="date" class="form-control" id="dor" name="dor">
                </div>
               
                <div class="form-group col-4 mb-4">
                    <label for="department_id">Department ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="department_id" name="department_id" required>
                </div>

                <!-- Buttons -->
                <div class="d-flex ">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary ms-3">Cancel</a>
                </div>
            </div>
            </form>
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
    });
</script>
@endsection
