<!DOCTYPE html>
<html>
<head>
    <title>Create Process Status</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Red+Rose:wght@300..700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/validation.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    
    <style>
        body {
            background-color: #f8f9fa; /* Light background for a clean look */
        }
        .dashboard-card {
            background-color: white;
            m-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .dashboard-header {
            background-color: #2E8B57; /* Ujjivan green color */
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            font-size: 20px;
            text-align: center;
        }
        .btn-ujjivan-green {
            background-color: #2E8B57;
            color: white;
            border: none;
        }
        .btn-ujjivan-green:hover {
            background-color: #276a4b;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="dashboard-header mb-4">
            <h2>Create New Process Status</h2>
        </div>

        <div class="dashboard-card">
            <form id="doc" action="{{ route('process_status.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <!-- Name Input -->
                <div class="mb-4">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" class="form-control" id="name" required pattern="^[a-zA-Z\s]+$" maxlength="55">
                    <!-- <div class="invalid-feedback">Please enter a valid name (alphabets and spaces only).</div> -->
                </div>

                <!-- Status Input -->
                <div class="mb-4">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" class="form-control" id="status" required>
                        <option value="">Select Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                    <!-- <div class="invalid-feedback">Please select a status.</div> -->
                </div>
                
                <div class="mb-4">
                    <label for="created_by" class="form-label">Created By:</label>
                    <select name="created_by" class="form-control" id="created_by" required>
                        <option value="">Select Creator</option>
                        <option value="1">Person 1</option>
                        <option value="2">Person 2</option>
                    </select>
                    <!-- <div class="invalid-feedback">Please select a creator.</div> -->
                </div>

                <!-- Updated By Input -->
                <div class="mb-4">
                    <label for="updated_by" class="form-label">Updated By:</label>
                    <select name="updated_by" class="form-control" id="updated_by" required>
                        <option value="">Select Updater</option>
                        <option value="1">Person 1</option>
                        <option value="2">Person 2</option>
                    </select>
                    <!-- <div class="invalid-feedback">Please select an updater.</div> -->
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-ujjivan-green">Save</button>
                    <a href="{{ route('process_status.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        // Add custom method for pattern validation
        $.validator.addMethod("regex", function (value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        }, "Please enter a valid value.");

        // Initialize validation
        $('#doc').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    regex: /^[a-zA-Z\s]+$/ // Custom pattern validation
                },
                status: {
                    required: true
                },
               
                created_by: {
                    required: true // Dropdown requires a value
                },
                updated_by: {
                    required: true // Optional dropdown
                }
            },
            messages: {
                name: {
                    required: "Please enter a name.",
                    minlength: "Name must be at least 3 characters long.",
                    regex: "Name can contain only alphabets and spaces."
                },
                status: {
                    required: "Please select a status."
                },
                
                created_by: {
                    required: "Please select a creator." // Updated for dropdown
                },
                updated_by: {
                    required: "Please select an updater." // Updated for dropdown (optional)
                }
            },
            submitHandler: function (form) {
                // Add custom logic here before form submission
                alert('Form is valid and ready to submit!');
                form.submit(); // Proceed to submit the form
            },
            errorPlacement: function (error, element) {
                // Customize error message placement
                error.insertAfter(element);
            }
        });

        // Optional: Prevent manual form submission
        $('#doc').on('submit', function (e) {
            if (!$('#doc').valid()) {
                e.preventDefault(); // Prevent submission if form is invalid
            }
        });
    });
</script>

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
    <!-- <script>
        // JavaScript for Bootstrap validation
        (function () { -->
    <!-- //         'use strict';

    //         const forms = document.querySelectorAll('.needs-validation');

    //         Array.prototype.slice.call(forms).forEach(function (form) {
    //             form.addEventListener('submit', function (event) {
    //                 if (!form.checkValidity()) {
    //                     event.preventDefault();
    //                     event.stopPropagation();
    //                 }

    //                 form.classList.add('was-validated');
    //             }, false);
    //         });
    //     })();
    // </script> -->
</body>
</html>
