<!DOCTYPE html>
<html>
<head>
    <title>View Process Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }

        .dashboard-header {
            background-color: #2E8B57;
            color: white;
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .info-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #565e64;
        }

        .error-feedback {
            color: red;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Header -->
        <div class="dashboard-header">
            View Process Status
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <form method="POST" action="{{ route('process_status.update', $status->id) }}" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                
                <!-- Name Field -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $status->name) }}" 
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status Field -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select 
                        class="form-select @error('status') is-invalid @enderror" 
                        id="status" 
                        name="status" 
                        required
                    >
                        <option value="1" {{ old('status', $status->status) == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $status->status) == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Created By Field -->
                <div class="mb-3">
                    <label for="created_by" class="form-label">Created By</label>
                    <input 
                        type="text" 
                        class="form-control @error('created_by') is-invalid @enderror" 
                        id="created_by" 
                        name="created_by" 
                        value="{{ old('created_by', $status->created_by) }}" 
                        required
                    >
                    @error('created_by')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Updated By Field -->
                <div class="mb-3">
                    <label for="updated_by" class="form-label">Updated By</label>
                    <input 
                        type="text" 
                        class="form-control @error('updated_by') is-invalid @enderror" 
                        id="updated_by" 
                        name="updated_by" 
                        value="{{ old('updated_by', $status->updated_by) }}"
                    >
                    @error('updated_by')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success">Save Changes</button>
            </form>
        </div>

        <!-- Back Button -->
        <div class="mt-4">
            <a href="{{ route('process_status.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Enable Bootstrap client-side validation
        (function () {
            'use strict'

            const forms = document.querySelectorAll('.needs-validation')

            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
