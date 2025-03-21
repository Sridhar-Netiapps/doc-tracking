@extends('layouts.admin')
@section('content')
    <div class="rightPanel">
        <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
            <div>
                <div class="d-flex justify-content-center align-items-center">
                    <h3 class="me-3">Process Status</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Library</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                        </ol>
                    </nav>
                </div>

            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-8">
                <div class="mt-2">
                    <div class="form-card">
                        <h2 class="mb-4">Create New Process Status</h2>
                        <div class="form-fields">
                            <form action="{{ route('process_status.store') }}" method="POST" id="processStatusForm"
                                  class="needs-validation" novalidate>
                                @csrf

                                <div class="mb-4">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" id="name" required
                                           pattern="^[a-zA-Z\s]+$" maxlength="55">
                                </div>



                                <div class="row">
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" class="form-select" id="status" required>
                                                <option value="">Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="mb-4">
                                            <label for="created_by" class="form-label">Created By</label>
                                            <select name="created_by" class="form-select" id="created_by" required>
                                                <option value="">Select Creator</option>
                                                <option value="1">Person 1</option>
                                                <option value="2">Person 2</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="updated_by" class="form-label">Updated By</label>
                                        <select name="updated_by" class="form-select" id="updated_by" required>
                                            <option value="">Select Updater</option>
                                            <option value="1">Person 1</option>
                                            <option value="2">Person 2</option>
                                        </select>
                                    </div>

                                </div>


                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="{{ route('process_status.index') }}"
                                       class="btn btn-secondary ms-2">Cancel</a>
                                </div>

                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>


        <script>
            $(document).ready(function () {
                $.validator.addMethod("regex", function (value, element, regexp) {
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                }, "Please enter a valid value.");

                $('#processStatusForm').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3,
                            regex: /^[a-zA-Z\s]+$/
                        },
                        status: {
                            required: true
                        },
                        created_by: {
                            required: true
                        },
                        updated_by: {
                            required: true
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
                            required: "Please select a creator."
                        },
                        updated_by: {
                            required: "Please select an updater."
                        }
                    },
                    submitHandler: function (form) {
                        alert('Form is valid and ready to submit!');
                        form.submit();
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element);
                    }
                });

                $('#processStatusForm').on('submit', function (e) {
                    if (!$('#processStatusForm').valid()) {
                        e.preventDefault();
                    }
                });
            });
        </script>
@endsection
