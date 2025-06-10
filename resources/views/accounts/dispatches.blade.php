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
            <h3>Dispatches</h3>
        </div>
        <div class="col-1"></div>
    </div>  
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                @hasanyrole('master|bo-maker|bo-checker|ro-user')
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','ready') }}" class="nav-link {{$type == 'ready' ? 'active':''}}" id="ready-tab" role="tab" aria-controls="ready-tab-pane" aria-selected="true">Ready to Dispatch @if ($ready_to_dispatch_count != 0)<span class="badge text-bg-warning">{{$ready_to_dispatch_count}}</span>@endif</a>
                </li>
                @endhasanyrole
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','list') }}" class="nav-link {{$type == 'list' ? 'active':''}}" id="list-tab" role="tab" aria-controls="list-tab-pane" aria-selected="false">Dispatched List @if ($dispatched_count != 0)<span class="badge text-bg-warning">{{$dispatched_count}}</span>@endif</a>
                </li>
                @hasanyrole('ro-user')
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','received') }}" class="nav-link {{$type == 'received' ? 'active':''}}" id="received-tab" role="tab" aria-controls="received-tab-pane" aria-selected="false">Delivered List @if ($received_count != 0)<span class="badge text-bg-warning">{{$received_count}}</span>@endif</a>
                </li>
                @endhasanyrole
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
                    <form method="POST" action="{{ route('dispatches.update') }}" id="proceed">
                        @csrf
                        <button class="btn btn-primary proceed" type="button">Update</button>
                    </form>
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
                                @if ($type == 'list')
                                    @hasanyrole('ro-user')
                                        <th scope="col">Update Status</th>
                                    @endhasanyrole
                                @endif
                                <th scope="col" class="border-start">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $row)
                                <tr>
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
                                    <td><p>MB Loan Doc - {{ $row->loan_ids!= null ? count(explode(',',$row->loan_ids)):0 }}</p>
                                        <p>Gold Loan Doc - {{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</p>
                                        <p>Liablities Doc - {{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</p>
                                        <p>DTR Files - {{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</p>
                                    </td>
                                    {{-- <td>{{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</td>
                                    <td>{{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</td>
                                    <td>{{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</td> --}}
                                    <td>{{ $row->dispatch_date }}</td>
                                    <td>{{ $row->creator->first_name }}</td>
                                    <td>{{ $row->statusName->name ?? '-' }}</td>
                                    @if ($type == 'list')
                                        @hasanyrole('ro-user')
                                        <td>
                                            <select id="remarks" name="remarks" class="form-control select2" required>
                                                <option value=5>Received</option>
                                                <option value=7>Received with Query</option>
                                                <option value=6>Rejected</option>
                                            </select>
                                            <textarea name="reason_for_rejection" class="form-control d-none" rows="2"></textarea>
                                        </td>
                                        @endhasanyrole
                                    @endif
                                    <td class="border-start">
                                        {{-- <a href="{{ route('dispatches.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a> --}}
                                        <div class="">
                                            <a href="{{ route('dispatches.view', $row->id) }}" class="border-0"><img src="/images/view_icon.svg"/></a>
                                            @if ($type == 'list')
                                                @hasanyrole('ro-user')
                                                    <button class="border-0" type="button"><img src="/images/send_icon.svg"/></button>
                                                @endhasanyrole
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="tab-pane fade {{$type == 'list' ? 'active show':''}}" id="list-tab-pane" role="tabpanel" aria-labelledby="list-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="select_all"/></th>
                                <th scope="col">AWB/POD Number</th>
                                <th scope="col">Courier Name</th>
                                <th scope="col">MMRP Internal Barcode No.</th>
                                <th scope="col">Branch code</th>
                                <th scope="col">Region</th>
                                <th scope="col">No of Loan Documents</th>
                                <th scope="col">No of Gold Loan Documents</th>
                                <th scope="col">No of DTRF Documents</th>
                                <th scope="col">No of AOF Documents</th>
                                <th scope="col">Dispatch Date</th>
                                <th scope="col">Dispatch By</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="border-start">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dispatched as $row)
                                <tr>
                                    <td><input type="checkbox" class="select" name="dispatch_ids[]" data-id="{{ $row->id }}" data-doc_type="{{ $row->doc_type }}"></td>  
                                    <td>{{ $row->awb_pod }}</td>
                                    <td>{{ $row->courier_name }}</td>
                                    <td>{{ $row->mmrp_barcode }}</td>
                                    <td>{{ $row->branch_code }}</td>
                                    <td>{{ $row->region }}</td>
                                    <td>{{ $row->loan_ids!= null ? count(explode(',',$row->loan_ids)):0 }}</td>
                                    <td>{{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</td>
                                    <td>{{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</td>
                                    <td>{{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</td>
                                    <td>{{ $row->dispatch_date }}</td>
                                    <td>{{ $row->creator->first_name }}</td>
                                    <td>{{ $row->statusName->name ?? '-' }}</td>
                                    <td class="border-start">
                                        <a href="{{ route('dispatches.view', $row->id) }}" class="btn btn-secondary btn-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
{{-- <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Toggle bottom offcanvas</button> --}}

{{-- <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasBottomLabel">Offcanvas bottom</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body small">
    <div class="tab-content bg-white" id="myTabContent">
        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
            <table class="table table-striped">
                <thead>
                    <tr> 
                        <th scope="col"><input type="checkbox" class="select_all"/> </th>     
                        <th scope="col"> Document Type</th>
                        <th scope="col"> Unique Number</th>
                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                        <th scope="col"> Region</th>
                        <th scope="col"> Branch Name</th>
                        @endunless
                        <th scope="col"> Branch Code</th>
                        <th scope="col"> CIF ID</th>
                        <th scope="col"> Account Number</th>
                        <th scope="col"> Loan Cycle</th>
                        <th scope="col"> Scheme</th>
                        <th scope="col"> Customer Name</th>
                        <th scope="col"> Account Creation Date</th>
                        <th scope="col"> Channel</th>
                        <th scope="col"> Loan Disbursement Type / Account Opening</th>
                        <th scope="col"> Business Category</th>
                        <th scope="col"> Status</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td></td>  
                            <td>doc_type </td>
                            <td> unique_ref_no</td>
                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                            <td> region</td>
                            <td> branch_name</td>
                            @endunless
                            <td> branch_code</td>
                            <td> cif_id</td>
                            <td> account_number</td>
                            <td> loan_cycle</td>
                            <td> scheme</td>
                            <td> customer_name</td>
                            <td> account_creation_date</td>
                            <td> channel</td>
                            <td> loan_disbursement_type  type_of_account_opening</td>
                            <td> business_category</td>
                            <td> statusName->name</td>
                        </tr>
                        <tr>
                            <td></td>  
                            <td>doc_type </td>
                            <td> unique_ref_no</td>
                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                            <td> region</td>
                            <td> branch_name</td>
                            @endunless
                            <td> branch_code</td>
                            <td> cif_id</td>
                            <td> account_number</td>
                            <td> loan_cycle</td>
                            <td> scheme</td>
                            <td> customer_name</td>
                            <td> account_creation_date</td>
                            <td> channel</td>
                            <td> loan_disbursement_type  type_of_account_opening</td>
                            <td> business_category</td>
                            <td> statusName->name</td>
                        </tr>
                        <tr>
                            <td></td>  
                            <td>doc_type </td>
                            <td> unique_ref_no</td>
                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                            <td> region</td>
                            <td> branch_name</td>
                            @endunless
                            <td> branch_code</td>
                            <td> cif_id</td>
                            <td> account_number</td>
                            <td> loan_cycle</td>
                            <td> scheme</td>
                            <td> customer_name</td>
                            <td> account_creation_date</td>
                            <td> channel</td>
                            <td> loan_disbursement_type  type_of_account_opening</td>
                            <td> business_category</td>
                            <td> statusName->name</td>
                        </tr>
                        <tr>
                            <td></td>  
                            <td>doc_type </td>
                            <td> unique_ref_no</td>
                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                            <td> region</td>
                            <td> branch_name</td>
                            @endunless
                            <td> branch_code</td>
                            <td> cif_id</td>
                            <td> account_number</td>
                            <td> loan_cycle</td>
                            <td> scheme</td>
                            <td> customer_name</td>
                            <td> account_creation_date</td>
                            <td> channel</td>
                            <td> loan_disbursement_type  type_of_account_opening</td>
                            <td> business_category</td>
                            <td> statusName->name</td>
                        </tr>
                        <tr>
                            <td></td>  
                            <td>doc_type </td>
                            <td> unique_ref_no</td>
                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                            <td> region</td>
                            <td> branch_name</td>
                            @endunless
                            <td> branch_code</td>
                            <td> cif_id</td>
                            <td> account_number</td>
                            <td> loan_cycle</td>
                            <td> scheme</td>
                            <td> customer_name</td>
                            <td> account_creation_date</td>
                            <td> channel</td>
                            <td> loan_disbursement_type  type_of_account_opening</td>
                            <td> business_category</td>
                            <td> statusName->name</td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</div> --}}
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
        $(".readytodispatch_all").click(function () {
            $(".readytodispatch").prop('checked', $(this).prop('checked'));
        });

        $('select[name="remarks"]').change(function (e) {
            if($(this).val() == '6' || $(this).val() == '7'){
                $('textarea[name="reason_for_rejection"]').removeClass('d-none');
                bootstrap.Offcanvas.getOrCreateInstance($('#offcanvasScrolling')[0]).show();
            }
            else{
                $('textarea[name="reason_for_rejection"]').addClass('d-none');
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
</script>

@endsection
