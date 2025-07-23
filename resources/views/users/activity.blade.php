@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">User List</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>Region</th>
                                <th>Branch Code</th>
                                <th>Email</th>
                                <th>Event Type</th>
                                <th>description</th>
                                {{-- <th>IP Address</th> --}}
                                <th>Activity on</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activites as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->user->first_name }} {{ $row->user->middle_name }} {{ $row->user->last_name }}</td>
                                    <td>{{ $row->user->employee_id }}</td>
                                    <td>{{ $row->user->region }}</td>
                                    <td>{{ $row->user->branch_id }}</td>
                                    <td>{{ $row->user->email }}</td>
                                    <td>{{ ucfirst($row->event_type) }}</td>
                                    <td>{{ $row->description }}</td>
                                    {{-- <td>{{ $row->ip_address }}</td> --}}
                                    <td>{{ date('d-m-Y h:i A', strtotime($row->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="">
                        {{ $activites->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
