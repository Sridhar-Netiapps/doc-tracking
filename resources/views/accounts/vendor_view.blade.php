@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="d-flex page-heading">
                <h3 > Moved to RMA</h3>
                {{-- <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Filters</button> --}}
            </div>
            @if(session('upload_failures'))
                <div class="alert alert-danger">
                    <strong>Import Failed for some rows:</strong>
                    <ul>
                        @foreach(session('upload_failures') as $failure)
                            <li>Row {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        {{-- <div class="col-1"></div>
            <div class="col-10"> --}}
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                {{-- @if ($loan_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="loanac-tab" data-bs-toggle="tab" data-bs-target="#loanac-tab-pane" type="button" role="tab" aria-controls="loanac-tab-pane" aria-selected="true">Loan Documents <span class="badge text-bg-warning">{{$loan_document != Null ?count($loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($gold_loan_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="goldloan-tab" data-bs-toggle="tab" data-bs-target="#goldloan-tab-pane" type="button" role="tab" aria-controls="goldloan-tab-pane" aria-selected="false">Gold Loan Documents <span class="badge text-bg-warning">{{$gold_loan_document != Null ?count($gold_loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($account_opening_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="aof-tab" data-bs-toggle="tab" data-bs-target="#aof-tab-pane" type="button" role="tab" aria-controls="aof-tab-pane" aria-selected="false">AOF Documents <span class="badge text-bg-warning">{{$account_opening_document != Null ?count($account_opening_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($dtrf_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dtrf-tab" data-bs-toggle="tab" data-bs-target="#dtrf-tab-pane" type="button" role="tab" aria-controls="dtrf-tab-pane" aria-selected="false">DTRF Documents <span class="badge text-bg-warning">{{$dtrf_document != Null ?count($dtrf_document):0}}</span></button>
                </li>                    
                {{-- @endif --}}
                @hasanyrole('ro-user')
                <li class="ms-auto">
                    <button id="update-all" class="btn btn-primary d-none">Update All</button>
                </li>
                @endhasanyrole
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="loanac-tab-pane" role="tabpanel" aria-labelledby="loanac-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">A/C No</th>
                                <th scope="col">Loan Cycle</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Channel</th>
                                <th scope="col">Type of Loan<br>Disbursement</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Lot No</th>
                                <th scope="col">Document Category</th>
                                <th scope="col">Work Order No</th>
                                <th scope="col">Vendor Name</th>
                                <th scope="col">Date of  Vendor Movement</th>
                                <th scope="col">File Barcode</th>
                                <th scope="col">Box Barcode</th>
                                <th scope="col">Date of addition to Vendor Data</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($loan_document)
                                @foreach ($loan_document as $row)
                                    <tr>
                                        <td>{{ $row->unique_ref_no }}</td>
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->loan_cycle }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td>{{ $row->loan_disbursement_type }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        <td>{{ $row->lot_no }}</td>
                                        <td>{{ $row->category_of_document }}</td>
                                        <td>{{ $row->work_order_no }}</td>
                                        <td>{{ $row->vendor_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                        <td>{{ $row->file_barcode }}</td>
                                        <td>{{ $row->box_barcode }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        <td>
                                            @if ($row->status == 8)
                                            <button type="submit" data-id="{{ $row->id }}" data-type="loan" class="btn btn-primary retrive">Update</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="goldloan-tab-pane" role="tabpanel" aria-labelledby="goldloan-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">A/C No</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Channel</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Lot No</th>
                                <th scope="col">Document Category</th>
                                <th scope="col">Work Order No</th>
                                <th scope="col">Vendor Name</th>
                                <th scope="col">Date of  Vendor Movement</th>
                                <th scope="col">File Barcode</th>
                                <th scope="col">Box Barcode</th>
                                <th scope="col">Date of addition to Vendor Data</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($gold_loan_document)
                                @foreach ($gold_loan_document as $row)
                                    <tr>
                                        <td>{{ $row->unique_ref_no }}</td>  
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td>{{ $row->business_category }}</td> 
                                        <td>{{ $row->lot_no }}</td>
                                        <td>{{ $row->category_of_document }}</td>
                                        <td>{{ $row->work_order_no }}</td>
                                        <td>{{ $row->vendor_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                        <td>{{ $row->file_barcode }}</td>
                                        <td>{{ $row->box_barcode }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        <td>
                                            @if ($row->status == 8)
                                            <button type="submit" data-id="{{ $row->id }}" data-type="goldloan" class="btn btn-primary retrive">Update</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="aof-tab-pane" role="tabpanel" aria-labelledby="aof-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">A/C No</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Channel</th>
                                <th scope="col">Type of Account Opening</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Lot No</th>
                                <th scope="col">Document Category</th>
                                <th scope="col">Work Order No</th>
                                <th scope="col">Vendor Name</th>
                                <th scope="col">Date of  Vendor Movement</th>
                                <th scope="col">File Barcode</th>
                                <th scope="col">Box Barcode</th>
                                <th scope="col">Date of addition to Vendor Data</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($account_opening_document)
                                @foreach ($account_opening_document as $row)
                                    <tr>
                                        <td>{{ $row->unique_ref_no }}</td>
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td>{{ $row->type_of_account_opening }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        <td>{{ $row->lot_no }}</td>
                                        <td>{{ $row->category_of_document }}</td>
                                        <td>{{ $row->work_order_no }}</td>
                                        <td>{{ $row->vendor_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                        <td>{{ $row->file_barcode }}</td>
                                        <td>{{ $row->box_barcode }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        <td>
                                            @if ($row->status == 8)
                                            <button type="submit" data-id="{{ $row->id }}" data-type="aof" class="btn btn-primary retrive">Update</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="dtrf-tab-pane" role="tabpanel" aria-labelledby="dtrf-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
                                <th scope="col">DTR File Date</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Lot No</th>
                                <th scope="col">Document Category</th>
                                <th scope="col">Work Order No</th>
                                <th scope="col">Vendor Name</th>
                                <th scope="col">Date of  Vendor Movement</th>
                                <th scope="col">File Barcode</th>
                                <th scope="col">Box Barcode</th>
                                <th scope="col">Date of addition to Vendor Data</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dtrf_document)
                                @foreach ($dtrf_document as $row)
                                    <tr>
                                        <td>{{ $row->unique_ref_no }}</td>
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date))}}</td>
                                        <td>{{ $row->business_category}}</td>
                                        <td>{{ $row->lot_no }}</td>
                                        <td>{{ $row->category_of_document }}</td>
                                        <td>{{ $row->work_order_no }}</td>
                                        <td>{{ $row->vendor_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                        <td>{{ $row->file_barcode }}</td>
                                        <td>{{ $row->box_barcode }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        <td>
                                            @if ($row->status == 8)
                                            <button type="submit" data-id="{{ $row->id }}" data-type="dtrf" class="btn btn-primary retrive">Update</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        {{-- </div>
        <div class="col-1"></div> --}}
    </div>
</div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <h5>Filters</h5>
        <form method="POST" action="{{ route('document.filter') }}">
            @csrf
            <div class="row">
                <div class="col-12 mt-3">
                    <select class="form-select document_type" name="document_type">
                        <option value="">Select Document Type</option>
                        <option value="loan" {{ ($filters['document_type'] ?? '') == 'loan' ? 'selected' : '' }}>MB Loan Documents</option>
                        <option value="gold_loan" {{ ($filters['document_type'] ?? '') == 'gold_loan' ? 'selected' : '' }}>Gold Loan Documents</option>
                        <option value="aof" {{ ($filters['document_type'] ?? '') == 'aof' ? 'selected' : '' }}>Liablities Documents</option>
                        <option value="dtrf" {{ ($filters['document_type'] ?? '') == 'dtrf' ? 'selected' : '' }}>DTR Files</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control unique_ref_no" placeholder="Unique Number" value="{{ old('unique_ref_no', $filters['unique_ref_no'] ?? '') }}" name="unique_ref_no">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="col-12 mt-3">
                    <select class="form-select region" name="region">
                        <option value="">Select Region</option>
                        <option value="South" {{ ($filters['region'] ?? '') == 'South' ? 'selected' : '' }}>South</option>
                        <option value="North" {{ ($filters['region'] ?? '') == 'North' ? 'selected' : '' }}>North</option>
                        <option value="East" {{ ($filters['region'] ?? '') == 'East' ? 'selected' : '' }}>East</option>
                        <option value="West" {{ ($filters['region'] ?? '') == 'West' ? 'selected' : '' }}>West</option>
                    </select>
                </div>
                @endunless
                <div class="col-12 mt-3">
                    <input type="text" class="form-control branch_code" placeholder="Branch Code" value="{{ old('branch_code', $filters['branch_code'] ?? '') }}" name="branch_code">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="col-12 mt-3">
                    <input type="text" class="form-control branch_name" placeholder="Branch Name" value="{{ old('branch_name', $filters['branch_name'] ?? '') }}" name="branch_name">
                </div>
                @endunless
                <div class="col-12 mt-3 d-none">
                    <input type="text" class="form-control cif_id" placeholder="CIF ID" value="{{ old('cif_id', $filters['cif_id'] ?? '') }}" name="cif_id">
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="text" class="form-control account_number" placeholder="A/C No" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="number" class="form-control loan_cycle" placeholder="Loan Cycle" value="{{ old('loan_cycle', $filters['loan_cycle'] ?? '') }}" name="loan_cycle">
                </div>
                <div class="col-12 mt-3 d-none">
                    <select class="form-select scheme" name="scheme">
                        <option value="">Select Scheme</option>
                        <option value="GL" {{ ($filters['scheme'] ?? '') == 'GL' ? 'selected' : '' }}>GL</option>
                        <option value="IL" {{ ($filters['scheme'] ?? '') == 'IL' ? 'selected' : '' }}>IL</option>
                    </select>
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="text" class="form-control customer_name" placeholder="Customer Name" value="{{ old('customer_name', $filters['customer_name'] ?? '') }}" name="customer_name">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control datepicker" placeholder="Date From" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control datepicker" placeholder="Date To" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
                </div>
                <div class="col-12 mt-3 d-none">
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
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control" placeholder="Business Category" value="{{ old('business_category', $filters['business_category'] ?? '') }}" name="business_category">
                </div>
                <div class="col-12 mt-3">
                    <select class="form-select" placeholder="Status" value="{{ old('status', $filters['status'] ?? '') }}" name="status">
                        <option value="">Select Status</option>
                        <option {{ ($filters['status'] ?? '') == 1 ? 'selected' : '' }} value=1>Pending</option>
                        <option {{ ($filters['status'] ?? '') == 3 ? 'selected' : '' }} value=3>Awaiting Checker Approval</option>
                        <option {{ ($filters['status'] ?? '') == 4 ? 'selected' : '' }} value="4">Dispatched</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a  href="{{ route('accounts.index','all') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="retrive" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="doc-retrive" action="{{ route('document.update') }}" method="POST">
                @csrf
                <div class="modal-header text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Retrive Document from RMA</h5>
                </div>
                <div class="modal-body">
                    <label for="status" class="form-label">Status</label>
                    <input type="hidden" name="id">
                    <input type="hidden" name="type">
                    <select name="remarks" class="form-control select2" required>
                        <option value=''>Select</option>
                        <option value='8'>In</option>
                        <option value='9'>Out</option>
                        <option value='10'>Permout</option>
                        <option value='11'>Destroyed</option>
                    </select>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.retrive').click(function () {
            $('input[name="id"]').val($(this).data('id'));
            $('input[name="type"]').val($(this).data('type'));
            $('#retrive').modal('show');
        });
        $('#doc-retrive').validate({
            rules: {
                status: {
                    required: true,
                    sanitize: true
                }
            }
        });
    });
</script>

@endsection


