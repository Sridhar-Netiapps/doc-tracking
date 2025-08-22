<!-- resources/views/emails/index.blade.php -->
{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Email Management</h2>

    <!-- Create Email Form -->
    <div class="card mb-4">
        <div class="card-header">New Email</div>
        <div class="card-body">
            <form action="{{ route('emails.store') }}" method="POST">
                @csrf
                @include('emails.form')
                <button type="submit" class="btn btn-success">Send Email</button>
            </form>
        </div>
    </div>

    <!-- Email List -->
    <h4>Existing Emails</h4>
    <table class="table table-bordered">
        <thead> --}}
@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Email Management</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

        </div>
        @role('master')
        <div><a href="{{ route('emails.create') }}" class="btn btn-primary">Create New Email</a></div>
        @endrole
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
            <tr>
                <th>ID</th>
                <th>To</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Sent At</th>
                @role('super_admin|master')
                <th>Actions</th>
                @endrole
            </tr>
        </thead>
        <tbody>
            @forelse($emails as $email)
            <tr>
                <td>{{ $email->id }}</td>
                <td>{{ $email->to }}</td>
                <td>{{ $email->subject }}</td>
                <td>{{ $email->status }}</td>
                <td>{{ $email->sent_at ?? '—' }}</td>
                @role('super_admin|master|admin')
                <td>
                    <a href="{{ route('emails.edit', $email->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    {{-- <form action="{{ route('emails.destroy', $email->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this email?')" class="btn btn-sm btn-danger">Delete</button>
                    </form> --}}
                </td>
                @endrole
            </tr>
            @empty
            <tr><td colspan="6" class="text-center">No emails found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
