@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div class="col-4 mb-4 form-group">
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
        @php
            echo "<pre>";
            print_r($user);
        @endphp
        @if(isset($user))
            <form id="users" action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="form-card row">

                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- First Name Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="first_name">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                        @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Middle Name Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="middle_name">Middle Name</label>
                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                        @error('middle_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Last Name Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="last_name">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                        @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Employee ID Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" required>
                        @error('employee_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-4 mb-4 form-group">
                        <label for="region">Region <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $user->office_region) }}" required>
                        @error('region')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-4 mb-4 form-group">
                        <label for="branch_id">Branch Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" value="{{ old('branch_id', $user->office_loc_code) }}" required>
                        @error('branch_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-4 mb-4 form-group">
                        <label for="email">Email Id <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->office_email) }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Gender Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="gender">Gender</label>
                        <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date of Birth Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="dob">Date of Birth <span class="text-danger">*</span></label>
                        <input type="text" readonly class="form-control datepicker @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob', $user->dob) }}" required>
                        @error('dob')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Status Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="active" {{ old('status', $user->employee_status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->employee_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Mobile Number Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->office_mobile) }}" required>
                        @error('mobile_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date of Joining Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="doj">Date of Joining <span class="text-danger">*</span></label>
                        <input type="text" readonly class="form-control datepicker @error('doj') is-invalid @enderror" id="doj" name="doj" value="{{ old('doj', $user->doj) }}" required>
                        @error('doj')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Date of Relieving Field -->
                    <div class="col-4 mb-4 form-group">
                        <label for="dor">Date of Relieving</label>
                        <input type="text" readonly class="form-control datepicker @error('dor') is-invalid @enderror" id="dor" name="dor" value="{{ old('dor', $user->doe) }}">
                        @error('dor')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-4 mb-4 form-group">
                        <label>Employee Type</label>
                        <input type="text" name="employee_type"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Current Designation</label>
                        <input type="text" name="current_designation"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Grade</label>
                        <input type="text" name="grade"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Confirmation Status</label>
                        <input type="text" name="confirmation_status"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Date of Confirmation</label>
                        <input type="text" name="date_of_confirmation"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Current Location Type</label>
                        <input type="text" name="current_location_type"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Direct Manager Name</label>
                        <input type="text" name="direct_manager_name"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Direct Manager Emp ID</label>
                        <input type="text" name="direct_manager_emp_id"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Direct Manager Email</label>
                        <input type="email" name="direct_manager_email"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Office Location</label>
                        <input type="text" name="office_location"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Current Department</label>
                        <input type="text" name="current_department"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Top Department</label>
                        <input type="text" name="top_department"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Department Hierarchy 1</label>
                        <input type="text" name="department_hierarchy_1_name"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Department Hierarchy 2</label>
                        <input type="text" name="department_hierarchy_2_name"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Department Hierarchy 3</label>
                        <input type="text" name="department_hierarchy_3_name"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Functional Head</label>
                        <input type="text" name="functional_head"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Functional Head Emp ID</label>
                        <input type="text" name="functional_head_emp_id"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>Work Flow Role</label>
                        <input type="text" name="work_flow_role"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>PRAC Designation</label>
                        <input type="text" name="prac_designation"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>PRAC Role</label>
                        <input type="text" name="prac_role"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>PAC Designation</label>
                        <input type="text" name="pac_designation"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                
                    <div class="col-4 mb-4 form-group">
                        <label>PAC Role</label>
                        <input type="text" name="pac_role"  value="{{ old('dor', $user->doe) }}" class="form-control">
                    </div>
                    <!-- Submit Button -->
                <div class="d-flex ">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <button type="button" class="btn btn-secondary ms-3" onclick="window.history.back()">Cancel</button>
                    </div>
                </div>
            </form>
        @else
            <form id="rma-upload" action="{{ route('users.create') }}" method="GET">
                <div class="col-6 row form-card">
                    <div class="col-6">
                        <input type="text" name="emp-id" class="form-control alphanumeric" placeholder="Enter Employee ID" required> 
                    </div>
                    <div class="col-3">
                        <button type="button" class="btn btn-primary btn-lg get-user"><strong>Get User Details</strong></button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
    $(document).ready(function () {  
        $(".datepicker").flatpickr({
                dateFormat: "d-m-Y",
                allowInput: true
        }); 
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

        // $('.get-user').click(function () {
        //     const id = $('input[name="emp-id"]').val();
        //     let url = "{{ url('users/create') }}/" + id;
        //     $.get(url);
        // });
    });
</script>
@endsection
