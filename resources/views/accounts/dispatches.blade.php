@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            {{-- <div class="filter-bg">
                <form method="POST" action="{{ route('accounts.index',$type) }}">
                    <div class="row">
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="unique_ref_no" placeholder="Unique Ref No" value="{{ request('unique_ref_no') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="branch_name" placeholder="Branch Name" value="{{ request('branch_name') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="branch_code" placeholder="Branch Code" value="{{ request('branch_code') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="cif_id" placeholder="CIF ID" value="{{ request('cif_id') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="account_number" placeholder="Account Number" value="{{ request('account_number') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <select class="form-control select2" name="region">
                                <option value="">Select Region</option>
                                <option value="South" {{ request('region') == 'South' ? 'selected' : '' }}>South</option>
                                <option value="North" {{ request('region') == 'North' ? 'selected' : '' }}>North</option>
                                <option value="East" {{ request('region') == 'East' ? 'selected' : '' }}>East</option>
                                <option value="West" {{ request('region') == 'West' ? 'selected' : '' }}>West</option>
                            </select>
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="date" placeholder="Unique Ref No" name="from_date" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="date" placeholder="Unique Ref No" name="to_date" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <button class="btn btn-secondary" type="reset">Clear</button>
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </form>
            </div> --}}
        </div>
        <div class="col-1"></div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="d-flex page-heading">
                <h3>Dispatches</h3>
                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Filters</button>
            </div>
        </div>
        <div class="col-1"></div>
    </div>  
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','ready') }}" class="nav-link {{$type == 'ready' ? 'active':''}}" id="ready-tab" role="tab" aria-controls="ready-tab-pane"  aria-selected="{{ $type == 'ready' ? 'true' : 'false' }}">Ready to Dispatch  @if ($type == 'ready' && $ready_to_dispatch_count != 0)<span class="badge text-bg-warning">{{$ready_to_dispatch_count}}</span>@endif</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','list') }}" class="nav-link {{$type == 'list' ? 'active':''}}" id="list-tab" role="tab" aria-controls="list-tab-pane" aria-selected="{{ $type == 'list' ? 'true' : 'false' }}">Courier Dispatched @if ($type == 'list' && $dispatched_count != 0)<span class="badge text-bg-warning">{{$dispatched_count}}</span>@endif</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','tracking') }}" class="nav-link {{$type == 'tracking' ? 'active':''}}" id="tracking-tab" role="tab" aria-controls="tracking-tab-pane" aria-selected="{{ $type == 'tracking' ? 'true' : 'false' }}">Pending for Tracking @if ($type == 'tracking' && $tracking_count != 0)<span class="badge text-bg-warning">{{$tracking_count}}</span>@endif</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','delivered') }}" class="nav-link {{$type == 'delivered' ? 'active':''}}" id="delivered-tab" role="tab" aria-controls="delivered-tab-pane" aria-selected="{{ $type == 'delivered' ? 'true' : 'false' }}">Courier Delivered  @if ($type == 'delivered' && $delivered_count != 0)<span class="badge text-bg-warning">{{$delivered_count}}</span>@endif</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','reject') }}" class="nav-link {{$type == 'reject' ? 'active':''}}" id="reject-tab" role="tab" aria-controls="reject-tab-pane" aria-selected="{{ $type == 'reject' ? 'true' : 'false' }}">Courier Rejected @if ($type == 'reject' && $reject_count != 0)<span class="badge text-bg-warning">{{$reject_count}}</span>@endif</a>
                </li>
                @if ($type == 'ready')
                @hasanyrole('bo-checker')
                <li class="ms-auto">
                    <form method="POST" action="{{ route('dispatched') }}" id="proceed">
                        @csrf
                        <button class="btn btn-primary proceed" type="button">Proceed to Dispatch</button>
                    </form>
                </li> 
                @endhasanyrole
                @endif
                @if ($type == 'list')
                @hasanyrole('ro-user')
                <li class="ms-auto">
                    <button id="update-all" class="btn btn-primary d-none">Update All</button>
                </li>
                @endhasanyrole
                @endif
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade active show" id="ready-tab-pane" role="tabpanel" aria-labelledby="ready-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @if ($type == 'ready')
                                @hasanyrole('master|bo-checker')
                                <th scope="col"><input type="checkbox" class="readytodispatch_all"/></th>
                                @endhasanyrole
                                @else
                                <th scope="col">Dispatch No</th>
                                @endif
                                <th scope="col">AWB/POD No</th>
                                <th scope="col">Courier Name</th>
                                <th scope="col">MMRP Code</th>
                                <th scope="col">Branch code</th>
                                {{-- <th scope="col">Region</th> --}}
                                <th scope="col">No of Documents</th>
                                {{-- <th scope="col">No of Gold Loan Documents</th>
                                <th scope="col">No of DTRF Documents</th>
                                <th scope="col">No of AOF Documents</th> --}}
                                <th scope="col">Dispatch Date</th>
                                <th scope="col">Dispatch By</th>
                                <th scope="col">Status</th>
                                <th scope="col">Activity Date</th>
                                @if ($type == 'list' || $type == 'tracking')
                                    @hasanyrole('ro-user')
                                        <th scope="col">Update Status</th>
                                    @endhasanyrole
                                @endif
                                <th scope="col" class="border-start">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $row)
                                <tr data-id="{{ $row->id }}" data-dispatch="{{ $row->dispatch_no }}">
                                    @if ($type == 'ready')
                                    @hasanyrole('master|bo-checker')
                                    <td><input type="checkbox" class="readytodispatch" name="readytodispatch_ids[]" data-id="{{ $row->id }}" data-doc_type="{{ $row->doc_type }}"></td>  
                                    @endhasanyrole
                                    @else
                                    <td>{{ $row->dispatch_no }}</td>
                                    @endif
                                    <td>{{ $row->awb_pod }}</td>
                                    <td>{{ $row->courierName->name }}</td>
                                    <td>{{ $row->mmrp_barcode }}</td>
                                    <td>{{ $row->branch_code }}</td>
                                    {{-- <td>{{ $row->region }}</td> --}}
                                    <td><p>MB Loan - {{ $row->loan_ids!= null ? count(explode(',',$row->loan_ids)):0 }}</p>
                                        <p>Gold Loan - {{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</p>
                                        <p>Liablities - {{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</p>
                                        <p>DTR Files - {{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</p>
                                    </td>
                                    {{-- <td>{{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</td>
                                    <td>{{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</td>
                                    <td>{{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</td> --}}
                                    <td>{{ $row->dispatch_date }}</td>
                                    <td>{{ $row->creator->first_name }}</td>
                                    <td>{{ $row->statusName->name ?? '-' }}
                                        @if ($row->status == 6 || $row->status == 7 )
                                            <small><p>Reason : </p></small>
                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->comments }}">
                                            <i>{{ \Illuminate\Support\Str::words($row->comments, 2, '...') }}</i>
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                    @if ($type == 'list')
                                        @hasanyrole('ro-user')
                                        <td>
                                            <select name="remarks" class="form-control select2 remarks" required>
                                                <option selected value=5>Received</option>
                                                <option value=7>Received with Query</option>
                                                <option value=6>Rejected</option>
                                            </select>
                                            <textarea name="reason_for_rejection" class="form-control reason d-none" rows="2"></textarea>
                                        </td>
                                        @endhasanyrole
                                    @endif
                                    @if ($type == 'tracking')
                                        @hasanyrole('ro-user')
                                        <td>
                                            <select name="remarks" class="form-control select2 remarks" required>
                                                <option selected value=12>Tracking Completed</option>
                                            </select>
                                            <textarea name="reason_for_rejection" class="form-control reason d-none" rows="2"></textarea>
                                        </td>
                                        @endhasanyrole
                                    @endif
                                    <td class="border-start">
                                        {{-- <a href="{{ route('dispatches.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a> --}}
                                        <div class="">
                                            <a href="{{ route('dispatches.view', $row->id) }}" class="border-0"><img src="/images/view_icon.svg"/></a>
                                            @if ($type == 'tracking')
                                                @hasanyrole('ro-user')
                                                    <button type="button"class="btn btn-sm btn-primary update-row disable-update-btn"  data-id="{{ $row->id }}" id="update-btn-{{ $row->id }}">Update</button>
                                                @endhasanyrole
                                            @endif
                                            @if ($type == 'list')
                                                @hasanyrole('ro-user')
                                                    <button type="button" value="12" class="btn btn-sm btn-primary update-row">Update</button>
                                                @endhasanyrole
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <h5>Filters</h5>
        <form method="POST" action="{{ route('dispatches.filter', $type) }}">
            @csrf
            <div class="row">
                {{-- <div class="col-12 mt-3">
                    <select class="form-select document_type" name="document_type">
                        <option value="">Select Document Type</option>
                        <option value="loan" {{ ($filters['document_type'] ?? '') == 'loan' ? 'selected' : '' }}>MB Loan Docs</option>
                        <option value="gold_loan" {{ ($filters['document_type'] ?? '') == 'gold_loan' ? 'selected' : '' }}>Gold Loan Docs</option>
                        <option value="aof" {{ ($filters['document_type'] ?? '') == 'aof' ? 'selected' : '' }}>Liablities Docs</option>
                        <option value="dtrf" {{ ($filters['document_type'] ?? '') == 'dtrf' ? 'selected' : '' }}>DTR Files</option>
                    </select>
                </div> --}}
                <div class="col-12 mt-3">
                    <input type="text" class="form-control dispatch_no" placeholder="Dispatch No" value="{{ old('dispatch_no', $filters['dispatch_no'] ?? '') }}" name="dispatch_no">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control awb_pod" placeholder="AWB/POD No" value="{{ old('awb_pod', $filters['awb_pod'] ?? '') }}" name="awb_pod">
                </div>
                {{-- @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="col-12 mt-3">
                    <select class="form-select region" name="region">
                        <option value="">Select Region</option>
                        <option value="South" {{ ($filters['region'] ?? '') == 'South' ? 'selected' : '' }}>South</option>
                        <option value="North" {{ ($filters['region'] ?? '') == 'North' ? 'selected' : '' }}>North</option>
                        <option value="East" {{ ($filters['region'] ?? '') == 'East' ? 'selected' : '' }}>East</option>
                        <option value="West" {{ ($filters['region'] ?? '') == 'West' ? 'selected' : '' }}>West</option>
                    </select>
                </div>
                @endunless --}}
                {{-- <div class="col-12 mt-3">
                    <input type="text" class="form-control courier_name" placeholder="Courier Name" value="{{ old('courier_name', $filters['courier_name'] ?? '') }}" name="courier_name">
                </div> --}}
                <div class="col-12 mt-3">
                    {{-- <select class="form-select" name="courier">
                        <option value="">Courier Name</option>
                        @foreach ($couriers as $courier)
                            <option value="{{ $courier->id }}" {{ ($filters['courier'] ?? '') == $courier->id ? 'selected' : '' }}>
                                {{ $courier->name }}
                            </option>
                        @endforeach
                    </select> --}}
                    <select class="form-select" name="courier" id="courierSelect">
                        <option value="">Courier Name</option>
                        @foreach ($couriers as $courier)
                            <option value="{{ $courier->id }}" {{ ($filters['courier'] ?? '') == $courier->id ? 'selected' : '' }}>
                                {{ $courier->name }}
                            </option>
                        @endforeach
                    </select>                  
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control mmrp_barcode" placeholder="MMRP Code" value="{{ old('mmrp_barcode', $filters['mmrp_barcode'] ?? '') }}" name="mmrp_barcode">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control branch_code" placeholder="Branch Code" value="{{ old('branch_code', $filters['branch_code'] ?? '') }}" name="branch_code">
                </div>
                {{-- <div class="col-12 mt-3">mmrp_code
                    <input type="search" class="form-control account_number" placeholder="Account Number" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
                </div> --}}
                {{-- <div class="col-12 mt-3 d-none">
                    <input type="number" class="form-control loan_cycle" placeholder="Loan Cycle" value="{{ old('loan_cycle', $filters['loan_cycle'] ?? '') }}" name="loan_cycle">
                </div>
                <div class="col-12 mt-3 d-none">
                    <select class="form-select scheme" name="scheme">
                        <option value="">Select Scheme</option>
                        <option value="GL" {{ ($filters['scheme'] ?? '') == 'GL' ? 'selected' : '' }}>GL</option>
                        <option value="IL" {{ ($filters['scheme'] ?? '') == 'IL' ? 'selected' : '' }}>IL</option>
                    </select>
                </div> --}}
                {{-- <div class="col-12 mt-3 d-none">
                    <input type="text" class="form-control customer_name" placeholder="Customer Name" value="{{ old('customer_name', $filters['customer_name'] ?? '') }}" name="customer_name">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control datepicker" placeholder="From Date" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control datepicker" placeholder="To Date" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
                </div> --}}
                {{-- <div class="col-12 mt-3 d-none">
                    <input type="text" class="form-control channel" placeholder="Channel" value="{{ old('channel', $filters['channel'] ?? '') }}" name="channel">
                </div>
                <div class="col-12 mt-3 d-none">
                    <select class="form-select" name="type">
                        <option value="">Loan Disbursement/Account Opening</option>
                        <option value="Esign" {{ ($filters['type'] ?? '') == 'Esign' ? 'selected' : '' }}>Esign</option>
                        <option value="Manual" {{ ($filters['type'] ?? '') == 'Manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="date" class="form-control" placeholder="DTR File Date" value="{{ old('dtr_file_date', $filters['dtr_file_date'] ?? '') }}" name="dtr_file_date">
                </div> --}}
                {{-- <div class="col-12 mt-3">
                    <input type="text" class="form-control" placeholder="Business Category" value="{{ old('business_category', $filters['business_category'] ?? '') }}" name="business_category">
                </div> --}}
                <div class="col-12 mt-3">
                    <select class="form-select" name="status">
                        <option value="">Select Status</option>
                        {{-- @foreach ($process_statuses as $status)
                            <option value="{{ $status->id }}" {{ ($filters['status'] ?? '') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach --}}
                        <option value="3" {{ ($filters['status'] ?? '') == '3' ? 'selected' : '' }}> Awaiting checker Approval </option>
                        <option value="4" {{ ($filters['status'] ?? '') == '4' ? 'selected' : '' }}> Dispatched </option>
                        <option value="5" {{ ($filters['status'] ?? '') == '5' ? 'selected' : '' }}> Received </option>
                        <option value="6" {{ ($filters['status'] ?? '') == '6' ? 'selected' : '' }}> Rejected </option>
                        <option value="7" {{ ($filters['status'] ?? '') == '7' ? 'selected' : '' }}> Received with query </option>
                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                        <option value="12" {{ ($filters['status'] ?? '') == '12' ? 'selected' : '' }}> Tracking Completed </option>
                        @endunless
                    </select>                                      
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dispatches.clear', $type ?? 'all') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="update-courier" action="/accounts-update" method="POST">
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary">Upload Vendor Movement Information</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-4">
                        <label>Unique Number</label>
                        <h5 class="unique_number">UJJ029921</h5>
                    </div>
                    <div class="col-4 pb-4">
                        <label>Customer Name</label>
                        <h5 class="customer_name">Cali</h5>
                    </div>
                    <div class="col-4 pb-4">
                        <label>Channel</label>
                        <h5 class="channel">GL</h5>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Lot No.</label>
                        <input type="text" name="lot_no" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Work Order No.</label>
                        <input type="text" name="work_order_no" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Vendor Name</label>
                        <input type="text" name="vendor_name" class="form-control" required>
                    </div>
                     <div class="col-4 pb-2">
                        <label for="vendor_movement_date" class="form-label">Dispatch Date</label>
                        <input type="text" readonly class="form-control datepicker vendor_movement_date" value="{{ request('vendor_movement_date') }}" name="vendor_movement_date" id="vendor_movement_date" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">File barcode againt Lot No.</label>
                        <input type="file" name="file_barcode" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Box Barcode</label>
                        <input type="file" name="box_barcode" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Date of addition vendor Data</label>
                        <input type="date" name="vendor_addition_date" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="In">In</option>
                            <option value="Out">Out</option>
                            <option value="Permout">Permout</option>
                            <option value="Destroyed">Destroyed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a href="/accounts-update" class="btn btn-primary btn-lg"><strong>Submit</strong></a>
                    {{-- <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button> --}}
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="offcanvas offcanvas-bottom" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <h5>Filters</h5>
    </div>
</div>
<script>
    $(document).ready(function () {
        var count = $('select[name="remarks"]').length;
        if(count > 0){
            $('#update-all').removeClass('d-none');
        }
        $(".readytodispatch_all").click(function () {
            $(".readytodispatch").prop('checked', $(this).prop('checked'));
        });
        $('#courierSelect').select2({
            placeholder: "Courier Name",
            width: '100%',
            dropdownAutoWidth: true
        });
        $('select[name="remarks"]').change(function () {
            const row = $(this).closest('tr');
            const reasonField = row.find('textarea[name="reason_for_rejection"]');

            if ($(this).val() === '6' || $(this).val() === '7') {
                reasonField.removeClass('d-none');
            } else {
                reasonField.addClass('d-none').val('');
            }
        });

        $('.proceed').click(function () {
            hasSelection = false;
            let ids = [];

            $('input.readytodispatch:checked').each(function () {
                ids.push($(this).data('id'));
            });

            $('#proceed').find('input[name$="_ids[]"]').remove();

            if (ids.length > 0) {
                hasSelection = true;
                ids.forEach(function (id) {
                    $('#proceed').append(
                        '<input type="hidden" name="readytodispatch_ids[]" value="' + id + '">'
                    );
                });
            }

            if (hasSelection) {
                Swal.fire({
                    title: "Alert!",
                    text: "Are You Sure ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "YES",
                    cancelButtonText: "NO"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#proceed').submit();                     
                    }
                });
            } else {
                Swal.fire({
                    title: "Warning!",
                    text: "Please select at least one Document.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            }
        });
   
    $('#applyFilter').click(function () {
            let status = $('#status').val()?.trim();
            let search = $('#search').val()?.trim();
            let dateFrom = $('#date_from').val()?.trim();
            let dateTo = $('#date_to').val()?.trim();

            // Add more filter fields if needed

            if (!status && !search && !dateFrom && !dateTo) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please select any filter option.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            } else {
                $('#filterForm').submit(); // or trigger AJAX filtering
            }
        });
    });

    function collectRowData(row) {
        const id = row.data('id');
        const dispatch = row.data('dispatch');
        const remarks = row.find('.remarks').val();
        const reason = row.find('.reason').val();

        if ((remarks == '6'|| remarks == '7') && !reason.trim()) {
            throw `Reason is required for this Dispatch No: #${dispatch}`;
        }

        return { id, remarks, reason_for_rejection: reason };
    }

    checkDocumentStatuses();

    // Then: attach click event handler to all buttons
    $('.update-row').on('click', function () {
        if ($(this).prop('disabled')) return; // Prevent if disabled

        const row = $(this).closest('tr');
        let data;

        try {
            data = [collectRowData(row)];
        } catch (err) {
            Swal.fire("Alert", err, "warning");
            return;
        }

        sendUpdateRequest(data);
    });

    // Reusable function to check status
    function checkDocumentStatuses() {
        $('.disable-update-btn').each(function () {
            let button = $(this);
            let dispatchId = button.data('id');

            $.ajax({
                url: '/dispatches/check-status/' + dispatchId,
                method: 'GET',
                success: function(response) {
                    if (response.disable_update) {
                        button.prop('disabled', true)
                              .css('background-color', '#a9a9a9') // gray
                              .css('border-color', '#a9a9a9')
                              .attr('title', 'Update disabled: one or more documents have status 4');
                    } else {
                        button.prop('disabled', false)
                              .removeAttr('title')
                              .css('background-color', '')  // default style
                              .css('border-color', '');
                    }
                },
                error: function() {
                    console.error('Status check failed for dispatch ID: ' + dispatchId);
                }
            });
        });
    }

    function sendUpdateRequest(payload) {
        $.ajax({
            url: '{{ route("dispatches.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                updates: payload
            },
            success: function () {
                Swal.fire("Success", "Update successful", "success").then(() => location.reload());
            },
            error: function () {
                Swal.fire("Error", "Update failed", "error");
            }
        });
    }
    

    // $('.disable-update-btn').each(function () {
    //     let button = $(this);
    //     let dispatchId = button.data('id');

    //     $.ajax({
    //         url: '/dispatches/check-status/' + dispatchId,
    //         method: 'GET',
    //         success: function(response) {
    //             if (response.disable_update) {
    //                 button.prop('disabled', true).attr('title', 'Update disabled: one or more documents have status 4');
    //             }
    //         },
    //         error: function() {
    //             console.error('Status check failed for dispatch ID: ' + dispatchId);
    //         }
    //     });
    // });

    // $('.update-row').on('click', function () {
    //     const row = $(this).closest('tr');
    //     let data;
    //     console.log(row);
        
    //     try {
    //         data = [collectRowData(row)];
    //     } catch (err) {
    //         Swal.fire("Alert", err, "warning");
    //         return;
    //     }

    //     sendUpdateRequest(data);
    // });
    

    // Handle bulk update
    $('#update-all').on('click', function () {
        const data = [];
        let hasError = false;

        $('tr[data-id]').each(function () {
            try {
                data.push(collectRowData($(this)));
            } catch (err) {
                Swal.fire("Alert", err, "warning");
                hasError = true;
                return false; // stop loop
            }
        });

        if (!hasError && data.length) {
            sendUpdateRequest(data);
        }
    });

    // Common AJAX function
    function sendUpdateRequest(payload) {
        $.ajax({
            url: '{{ route("dispatches.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                updates: payload
            },
            success: function () {
                Swal.fire("Success", "Update successful", "success").then(() => location.reload());
            },
            error: function () {
                Swal.fire("Error", "Update failed", "error");
            }
        });
    }
</script>

@endsection
