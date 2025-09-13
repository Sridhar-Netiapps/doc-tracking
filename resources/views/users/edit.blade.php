@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Edit User</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/users">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="h-100 align-items-start align-content-lg-stretch">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
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
                    <input type="text" class="form-control alphanumeric @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                    @error('first_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Middle Name Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="middle_name">Middle Name</label>
                    <input type="text" class="form-control alphanumeric @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $user->middle_name) }}">
                    @error('middle_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Last Name Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control alphanumeric @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                    @error('last_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Employee ID Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="employee_id">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" class="form-control alphanumeric @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" required>
                    @error('employee_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4 mb-4 form-group">
                    <label for="region">Region <span class="text-danger">*</span></label>
                    <input type="text" class="form-control alphanumeric @error('region') is-invalid @enderror" id="region" name="region" value="{{ old('region', $user->region) }}" required>
                    @error('region')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4 mb-4 form-group">
                    <label for="branch_id">Branch Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control alphanumeric @error('branch_id') is-invalid @enderror" id="branch_id" name="branch_id" value="{{ old('branch_id', $user->branch_id) }}" required>
                    @error('branch_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-4 mb-4 form-group">
                    <label for="email">Email Id <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
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
                    <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob', $user->dob) }}" required>
                    @error('dob')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Status Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Mobile Number Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="mobile_number">Mobile Number <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" required min="0">
                    @error('mobile_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date of Joining Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="doj">Date of Joining <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('doj') is-invalid @enderror" id="doj" name="doj" value="{{ old('doj', $user->doj) }}" required>
                    @error('doj')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Date of Relieving Field -->
                <div class="col-4 mb-4 form-group">
                    <label for="dor">Date of Relieving</label>
                    <input type="date" class="form-control @error('dor') is-invalid @enderror" id="dor" name="dor" value="{{ old('dor', $user->dor) }}">
                    @error('dor')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
               <div class="d-flex ">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <button type="button" class="btn btn-secondary ms-3" onclick="window.history.back()">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    <h2 class="mt-5">Assign Roles</h2>
    <div class="h-100 align-items-start align-content-lg-stretch">
        <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
            @csrf
            <div class="form-card row">
                <div class="col-6 form-group">
                    <label for="role">Roles</label>
                    <select name="role" class="form-control">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 form-group mt-4">
                    <button type="submit" class="btn btn-primary">Assign Role</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
