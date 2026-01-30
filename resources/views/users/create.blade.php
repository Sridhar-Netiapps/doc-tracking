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
                    <label for="first_name">First Name</label>
                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" readonly>
                    @error('first_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Middle Name Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}" readonly>
                    @error('middle_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Last Name Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" readonly>
                    @error('last_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Employee ID Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" class="form-control @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" readonly>
                    @error('employee_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4 mb-4 form-group">
                    <label for="region">Region</label>
                    <input type="text" class="form-control @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $user->office_region) }}" readonly>
                    <input type="hidden" name="region_id" value="{{ old('region_id', $user->region_id) }}">
                    @error('region')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4 mb-4 form-group">
                    <label for="branch_id">Branch Code</label>
                    <input type="text" class="form-control @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" value="{{ old('branch_id', $user->office_loc_code) }}" readonly>
                    @error('branch_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4 mb-4 form-group">
                    <label for="email">Email Id</label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->office_email) }}" readonly>
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
                    <label for="dob">Date of Birth</label>
                    <input type="text" readonly class="form-control datepicker @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob', $user->dob) }}" readonly>
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
                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->office_mobile) }}" readonly>
                    @error('mobile_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date of Joining Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="doj">Date of Joining</label>
                    <input type="text" readonly class="form-control datepicker @error('doj') is-invalid @enderror" id="doj" name="doj" value="{{ old('doj', $user->doj) }}" readonly>
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
                    <input type="text" name="employee_type"  value="{{ old('employee_type', $user->employee_type) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Current Designation</label>
                    <input type="text" name="current_designation"  value="{{ old('current_designation', $user->current_designation) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Grade</label>
                    <input type="text" name="grade"  value="{{ old('grade', $user->grade) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Confirmation Status</label>
                    <input type="text" name="confirmation_status"  value="{{ old('confirmation_status', $user->confirmation_status) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Date of Confirmation</label>
                    <input type="text" name="date_of_confirmation"  value="{{ old('date_of_confirmation', $user->date_of_confirmation) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Current Location Type</label>
                    <input type="text" name="current_location_type"  value="{{ old('current_location_type', $user->current_location_type) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Direct Manager Name</label>
                    <input type="text" name="direct_manager_name"  value="{{ old('direct_manager_name', $user->direct_manager_name) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Direct Manager Emp ID</label>
                    <input type="text" name="direct_manager_emp_id"  value="{{ old('direct_manager_emp_id', $user->direct_manager_emp_id) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Direct Manager Email</label>
                    <input type="email" name="direct_manager_email"  value="{{ old('direct_manager_email', $user->direct_manager_email) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Office Location</label>
                    <input type="text" name="office_location"  value="{{ old('office_location', $user->office_location) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Current Department</label>
                    <input type="text" name="current_department"  value="{{ old('current_department', $user->current_department) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Top Department</label>
                    <input type="text" name="top_department"  value="{{ old('top_department', $user->top_department) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Department Hierarchy 1</label>
                    <input type="text" name="department_hierarchy_1_name"  value="{{ old('department_hierarchy_1_name', $user->department_hierarchy_1_name) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Department Hierarchy 2</label>
                    <input type="text" name="department_hierarchy_2_name"  value="{{ old('department_hierarchy_2_name', $user->department_hierarchy_2_name) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Department Hierarchy 3</label>
                    <input type="text" name="department_hierarchy_3_name"  value="{{ old('department_hierarchy_3_name', $user->department_hierarchy_3_name) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Functional Head</label>
                    <input type="text" name="functional_head"  value="{{ old('functional_head', $user->functional_head) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Functional Head Emp ID</label>
                    <input type="text" name="functional_head_emp_id"  value="{{ old('functional_head_emp_id', $user->functional_head_emp_id) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>Work Flow Role</label>
                    <input type="text" name="work_flow_role"  value="{{ old('work_flow_role', $user->work_flow_role) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>PRAC Designation</label>
                    <input type="text" name="prac_designation"  value="{{ old('prac_designation', $user->prac_designation) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>PRAC Role</label>
                    <input type="text" name="prac_role"  value="{{ old('prac_role', $user->prac_role) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>PAC Designation</label>
                    <input type="text" name="pac_designation"  value="{{ old('pac_designation', $user->pac_designation) }}" class="form-control" readonly>
                </div>
            
                <div class="col-4 mb-4 form-group">
                    <label>PAC Role</label>
                    <input type="text" name="pac_role"  value="{{ old('pac_role', $user->pac_role) }}" class="form-control" readonly>
                </div>
                <h2 class="mt-2">Assign Roles</h2>
                <div class="h-100 align-items-start align-content-lg-stretch">
                    <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
                        @csrf
                        <div class="form-card row">
                            <div class="col-6 form-group">
                                <label for="role">Roles</label>
                                <select name="role" class="form-control" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        @if ($role->name !== 'master')
                                            <option value="{{ $role->name }}">
                                                {{-- {{ $role->name }} --}}
                                                {{ $role->name === 'super_admin' ? 'id_maintenance' : $role->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>  
                            </div>
                            {{-- <div class="col-6 form-group mt-4">
                                <button type="submit" class="btn btn-primary">Assign Role</button>
                            </div> --}}
                        </div>
                    </form>
                </div>
                <!-- Submit Button -->

                 <h2 class="mt-2">Assign Module(s)</h2>

               <div class="form-check form-check-inline ms-5">
                  <input class="form-check-input" type="checkbox" name="module_role" value="doc" id="inlineCheckbox1">
                  <label class="form-check-label" for="inlineCheckbox1">DocTrack</label>
                </div>
                <div class="form-check form-check-inline ms-5">
                  <input class="form-check-input" type="checkbox" name="module_role" value="ins" id="inlineCheckbox2">
                  <label class="form-check-label" for="inlineCheckbox2">Insurance</label>
                </div>
                <div class="form-check form-check-inline ms-5">
                  <input class="form-check-input" type="checkbox" name="module_role" value="doc_ins" id="inlineCheckbox3">
                  <label class="form-check-label" for="inlineCheckbox3">DocTrack and Insurance</label>
                </div>



            <div class="d-flex mt-3">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="{{ route('users.index') }}" type="button" class="btn btn-secondary ms-3">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function() {

  // Handle Option 1 and 2
  $('#inlineCheckbox1, #inlineCheckbox2').on('change', function() {
    // If both 1 and 2 are checked
    if ($('#inlineCheckbox1').is(':checked') && $('#inlineCheckbox2').is(':checked')) {
      $('#inlineCheckbox3').prop('checked', true);
      $('#inlineCheckbox1, #inlineCheckbox2').prop('checked', false);
    } else {
      // If either 1 or 2 is checked, uncheck 3
      $('#inlineCheckbox3').prop('checked', false);
    }
  });

  // Handle Option 3
  $('#inlineCheckbox3').on('change', function() {
    if ($(this).is(':checked')) {
      // Uncheck 1 and 2
      $('#inlineCheckbox1, #inlineCheckbox2').prop('checked', false);
    }
  });

  $('#users').on('submit', function(e) {
    if (!$('#inlineCheckbox1').is(':checked') && 
        !$('#inlineCheckbox2').is(':checked') && 
        !$('#inlineCheckbox3').is(':checked')) {
      e.preventDefault();
      alert('Please assign module.');
    }
  });


});
</script>
@endsection
