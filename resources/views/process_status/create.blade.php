@extends('layouts.admin')  
@section('content') 
<div class="rightPanel">     
    <div class="d-flex justify-content-between align-items-center mb-2">         
        <h3>Process Status</h3>         
        <!-- <a href="{{ route('process_status.create') }}" class="btn btn-primary">Create New Process Status</a> -->     
    </div>       

    @if (session('success'))         
        <div class="alert alert-success mt-3">             
            {{ session('success') }}         
        </div>     
    @endif     

    <div class="container mt-5">         
        <div class="dashboard-header mb-4">             
            <h2>Create New Process Status</h2>         
        </div>          

        <div class="dashboard-card">             
            <form action="{{ route('process_status.store') }}" method="POST" id="processStatusForm" class="needs-validation" novalidate>                 
                @csrf                  

                <div class="mb-4">                     
                    <label for="name" class="form-label">Name:</label>                     
                    <input type="text" name="name" class="form-control" id="name" required pattern="^[a-zA-Z\s]+$" maxlength="55">                 
                </div>                  

                <div class="mb-4">                     
                    <label for="status" class="form-label">Status:</label>                     
                    <select name="status" class="form-control" id="status" required>                         
                        <option value="">Select Status</option>                         
                        <option value="1">Active</option>                         
                        <option value="0">Inactive</option>                     
                    </select>                                      
                </div>                                  

                <div class="mb-4">                     
                    <label for="created_by" class="form-label">Created By:</label>                     
                    <select name="created_by" class="form-control" id="created_by" required>                         
                        <option value="">Select Creator</option>                         
                        <option value="1">Person 1</option>                         
                        <option value="2">Person 2</option>                     
                    </select>                                     
                </div>                  

                <div class="mb-4">                     
                    <label for="updated_by" class="form-label">Updated By:</label>                     
                    <select name="updated_by" class="form-control" id="updated_by" required>                         
                        <option value="">Select Updater</option>                         
                        <option value="1">Person 1</option>                         
                        <option value="2">Person 2</option>                     
                    </select>                                      
                </div>                  

                    <div class="d-flex">
                    <button type="submit" class="btn btn-primary ">Save</button>
                    <a href="{{ route('process_status.index') }}" class="btn btn-secondary ml-2">Cancel</a>
                </div>
            
            </form>         
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
