{{-- <!-- resources/views/emails/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create New Email</h2> --}}
    @extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Create New Mail </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">

    <form id="emails" action="{{ route('emails.store') }}" method="POST">
        @csrf
        @include('emails.form')
        <div class="d-flex">
            <button type="submit" class="btn btn-primary me-3">Save</button>
            <a href="{{ route('emails.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {
        $("#emailForm").on("submit", function () {
            $(".text-danger").html(""); // Clear previous errors
        });

        $("#emailForm").validate({
            rules: {
                sender: { required: true, email: true, sanitize: true },
                to: { required: true, email: true, sanitize: true },
                cc: { email: true, sanitize: true },
                bcc: { email: true, sanitize: true },
                subject: { required: true, sanitize: true },
                message: { required: true, sanitize: true },
            },
            messages: {
                sender: { required: "Sender email is required", email: "Enter a valid email" },
                to: { required: "Recipient email is required", email: "Enter a valid email" },
                cc: { email: "Enter a valid email" },
                bcc: { email: "Enter a valid email" },
                subject: { required: "Subject is required" },
                message: { required: "Message is required" },
            },
            errorPlacement: function (error, element) {
                error.addClass("text-danger");
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to submit this email?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, send it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>

@endsection
