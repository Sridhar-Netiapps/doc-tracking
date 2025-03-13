<!DOCTYPE html>
<html>
<head>
    <title>Edit Process Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <style>
        body {
            background-color: #f8f9fa; /* Light background for a clean look */
        }

        .dashboard-header {
            background-color: #2E8B57; /* Ujjivan green color */
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .ujjivan-green {
            background-color: #2E8B57;
            color: white;
            border: none;
        }

        .ujjivan-green:hover {
            background-color: #276a4b;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- Header -->
        <div class="dashboard-header">
            Edit Process Status
        </div>

        <!-- Form Card -->
        <div class="dashboard-card">
            <form id= "doc"action="{{ route('process_status.update', $status->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Name Input -->
                <div class="mb-4">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $status->name }}" required placeholder="Enter status name">
                </div>

                <!-- Status Dropdown -->
                <div class="mb-4">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="1" {{ $status->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $status->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <!-- Created By (Read-Only) -->
                <div class="mb-4">
                    <label for="created_by" class="form-label">Created By</label>
                    <input type="text" class="form-control" value="{{ $status->created_by }}" readonly>
                </div>

                <!-- Updated By Dropdown -->
                <div class="mb-4">
                    <label for="updated_by" class="form-label">Updated By</label>
                    <select name="updated_by" class="form-control" required>
                        <option value="1" {{ $status->updated_by == 'Person 1' ? 'selected' : '' }}>Person 1</option>
                        <option value="2" {{ $status->updated_by == 'Person 2' ? 'selected' : '' }}>Person 2</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn ujjivan-green">Update</button>
                    <a href="{{ route('process_status.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
