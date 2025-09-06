@extends('layouts.admin')

@section('content')
<div class="rightPanel">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Audit Logs</h3>
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item"><a href="/library">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav> --}}
                <button class="btn btn-sm btn-primary me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Filters</button>
                 {{-- <form id="exportForm" method="GET" action="{{ route('activity.export') }}"> --}}
        <form method="GET" action="{{ route('activity.export') }}">
            @foreach(($filters ?? []) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach        
            {{-- <button type="button" id="exportBtn" class="btn btn-success"> --}}
            <button type="submit" class="btn btn-sm btn-success">
                Export
            </button>
        </form>
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
                                <th>IP Address</th>
                                <th>Event Type</th>
                                <th>description</th>
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
                                    <td>{{ ucfirst($row->ip_address) }}</td>
                                    <td>{{ ucfirst($row->event_type) }}</td>
                                    <td>
                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->description }}">
                                            <i>{{ \Illuminate\Support\Str::words($row->description, 1, '...') }}</i>
                                        </span>
                                    </td>
                                    {{-- <td>{{ $row->ip_address }}</td> --}}
                                    <td>{{ date('d-m-Y h:i A', strtotime($row->created_at)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="">
                        {{ $activites->links('pagination::bootstrap-5') }}
                    </div> --}}
                    <div class="">
                        {{ $activites->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <h5>Filters</h5>
        <form method="POST" action="{{ route('activity.filter') }}">
            @csrf
            <div class="row">
                <div class="col-12 mt-3">
                    <select class="form-select region" name="region">
                        <option value="">Select Region</option>
                        <option value="South" {{ ($filters['region'] ?? '') == 'South' ? 'selected' : '' }}>South</option>
                        <option value="North" {{ ($filters['region'] ?? '') == 'North' ? 'selected' : '' }}>North</option>
                        <option value="East" {{ ($filters['region'] ?? '') == 'East' ? 'selected' : '' }}>East</option>
                        <option value="West" {{ ($filters['region'] ?? '') == 'West' ? 'selected' : '' }}>West</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <input type="number" class="form-control branch_id" placeholder="Branch Code" value="{{ old('branch_id', $filters['branch_id'] ?? '') }}" name="branch_id" min="0">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control employee_id alphanumeric" placeholder="Employee ID" value="{{ old('employee_id', $filters['employee_id'] ?? '') }}" name="employee_id">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Activity From" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Activity To" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('users.activities') }}" class="btn btn-secondary">Clear</a> 
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Bootstrap Modal -->
{{-- <div class="modal fade" id="filterAlertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <p class="mb-0">Filter the data first</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
</div> --}}
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {
        // $(document).on('click', '#exportBtn', function () {
        //     let formData = $('#exportForm').serialize();

        //     $.ajax({
        //         url: '{{ route("activity.export.check") }}',
        //         type: 'GET',
        //         data: formData,
        //         success: function (response) {
        //             if (response.status === 'error') {
        //                 Swal.fire("Warning!", "Filter the data first.", "warning");
        //             } else {
        //                 $('#exportForm')[0].submit();
        //             }
        //         }
        //     });
        // });
        flatpickr(".flatpickr-date", {
            dateFormat: "d-m-Y",        
            maxDate: "today",         
            allowInput: false, 
            clickOpens: true
            // altFormat: "d-m-Y"  
        });
    });
</script>
@endsection
