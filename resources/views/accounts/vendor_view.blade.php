@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="d-flex page-heading">
                <h3 > Moved to RMA</h3>
                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Filters</button>
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
                    <button class="nav-link {{($filters['document_type'] ?? 'loan') == 'loan' ? 'active':''}} " id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan-tab-pane" type="button" role="tab" aria-controls="loan-tab-pane" aria-selected="true">Loan Documents <span class="badge text-bg-warning">{{$loan_document != Null ?count($loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($gold_loan_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'gold_loan' ? 'active':''}}" id="goldloan-tab" data-bs-toggle="tab" data-bs-target="#goldloan-tab-pane" type="button" role="tab" aria-controls="goldloan-tab-pane" aria-selected="false">Gold Loan Documents <span class="badge text-bg-warning">{{$gold_loan_document != Null ?count($gold_loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($account_opening_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'aof' ? 'active':''}}" id="aof-tab" data-bs-toggle="tab" data-bs-target="#aof-tab-pane" type="button" role="tab" aria-controls="aof-tab-pane" aria-selected="false">AOF Documents <span class="badge text-bg-warning">{{$account_opening_document != Null ?count($account_opening_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($dtrf_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'dtrf' ? 'active':''}}" id="dtrf-tab" data-bs-toggle="tab" data-bs-target="#dtrf-tab-pane" type="button" role="tab" aria-controls="dtrf-tab-pane" aria-selected="false">DTRF Documents <span class="badge text-bg-warning">{{$dtrf_document != Null ?count($dtrf_document):0}}</span></button>
                </li>                    
                {{-- @endif --}}
                @hasanyrole('ro-user')
                <li class="ms-auto">
                    <button id="update-all" class="btn btn-primary d-none">Update All</button>
                </li>
                @endhasanyrole
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="loan-tab-pane" role="tabpanel" aria-labelledby="loan-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">A/C No</th>
                                <th scope="col">Loan Cycle</th>
                                <th scope="col">Loan Amount</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">Glow Application ID</th>
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
                                        <td>{{ $row->loan_amount }}</td>
                                        <td>{{ $row->barcode }}</td>
                                        <td>{{ $row->glow_application_id }}</td>
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
                                            @if ($row->status != 11)
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
                                <th scope="col">Loan Amount</th>
                                <th scope="col">Barcode</th>
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
                                        <td>{{ $row->loan_amount }}</td>
                                        <td>{{ $row->barcode }}</td>
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
                                            @if ($row->status != 11)
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
                                <th scope="col">Scheme</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">PGK No</th>
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
                                        <td>{{ $row->scheme }}</td>
                                        <td>{{ $row->barcode }}</td>
                                        <td>{{ $row->pgk_no }}</td>
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
                                            @if ($row->status != 11)
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
                                <th scope="col">Barcode</th>
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
                                        <td>{{ $row->barcode }}</td>
                                        <td>{{ $row->category_of_document }}</td>
                                        <td>{{ $row->work_order_no }}</td>
                                        <td>{{ $row->vendor_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                        <td>{{ $row->file_barcode }}</td>
                                        <td>{{ $row->box_barcode }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        <td>
                                            @if ($row->status != 11)
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
            <input type="hidden" name="doc_type" value="{{$type}}">
            <div class="row">
                <div class="col-12 mt-3">
                    <select class="form-select document_type" name="document_type">
                        <option value="">Select Document Type</option>
                        <option value="loan" {{ ($filters['document_type'] ?? '') == 'loan' ? 'selected' : '' }}>MB Loan Docs</option>
                        <option value="gold_loan" {{ ($filters['document_type'] ?? '') == 'gold_loan' ? 'selected' : '' }}>Gold Loan Docs</option>
                        <option value="aof" {{ ($filters['document_type'] ?? '') == 'aof' ? 'selected' : '' }}>Liablities Docs</option>
                        <option value="dtrf" {{ ($filters['document_type'] ?? '') == 'dtrf' ? 'selected' : '' }}>DTR Files</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control unique_ref_no" placeholder="Unique Number" value="{{ old('unique_ref_no', $filters['unique_ref_no'] ?? '') }}" name="unique_ref_no">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="col-12 mt-3 d-none">
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
                <div class="col-12 mt-3">
                    <input type="text" class="form-control cif_id" placeholder="CIF ID" value="{{ old('cif_id', $filters['cif_id'] ?? '') }}" name="cif_id">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control account_number" placeholder="A/C No" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control" placeholder="Business Category" value="{{ old('business_category', $filters['business_category'] ?? '') }}" name="business_category">
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="number" class="form-control loan_cycle" placeholder="Loan Cycle" value="{{ old('loan_cycle', $filters['loan_cycle'] ?? '') }}" name="loan_cycle" min=0>
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control category_of_document" placeholder="Document Category" value="{{ old('category_of_document', $filters['category_of_document'] ?? '') }}" name="category_of_document">
                </div>
                <div class="col-12 mt-3">
                    <input type="number" class="form-control work_order_no" placeholder="Work Order No" value="{{ old('work_order_no', $filters['work_order_no'] ?? '') }}" name="work_order_no" min="0">
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
                    <select class="form-select" placeholder="Status" value="{{ old('status', $filters['status'] ?? '') }}" name="status">
                        <option value=''>Select Status</option>
                            <option value='8' {{ ($filters['status'] ?? '') == 8 ? 'selected' : '' }}>In</option>
                            <option value='9' {{ ($filters['status'] ?? '') == 9 ? 'selected' : '' }}>Out</option>
                            <option value='10' {{ ($filters['status'] ?? '') == 10 ? 'selected' : '' }}>Permout</option>
                            <option value='11' {{ ($filters['status'] ?? '') == 11 ? 'selected' : '' }}>Destroyed</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a  href="{{ route('accounts.index',$type) }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="retrive" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            {{-- <form id="doc-retrive" action="{{ route('document.update') }}" method="POST"> --}}
            <form id="doc-retrive" action="{{ route('accounts.moved')}}" method="POST">
                @csrf
                <div class="modal-header text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Retrive Document from RMA</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-2">
                        <input type="hidden" name="id">
                        <input type="hidden" name="type">
                        <label for="lot_no" class="form-label">Lot No.</label>
                        <input type="text" name="lot_no" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="category_of_document" class="form-label">Category of the Document.</label>
                        {{-- <input type="text" name="category_of_document" class="form-control"> --}}
                        <select class="form-select document_type" name="category_of_document" required>
                            <option value="">Select Document Category</option>
                            <option value="cat_a1" {{ ($doc->category_of_document ?? '') == 'cat_a1' ? 'selected' : '' }}>CAT A1</option>
                            <option value="cat_a2" {{ ($doc->category_of_document ?? '') == 'cat_a2' ? 'selected' : '' }}>CAT A2</option>
                            <option value="cat_b"  {{ ($doc->category_of_document ?? '') == 'cat_b'  ? 'selected' : '' }}>CAT B</option>
                            <option value="cat_c"  {{ ($doc->category_of_document ?? '') == 'cat_c'  ? 'selected' : '' }}>CAT C</option>
                        </select>                                               
                    </div>
                    <div class="col-4 pb-2">
                        <label for="work_order_no" class="form-label">Work Order No.</label>
                        <input type="text" name="work_order_no" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_name" class="form-label">Vendor Name</label>
                        {{-- <input type="text" name="vendor_name" class="form-control"> --}}
                        <select class="form-select" name="vendor_name" required>
                            <option value="">Select Vendor Name</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->name }}" {{ ($doc->vendor_name ?? '') == $vendor->name ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>                            
                            @endforeach
                        </select>                                                
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_movement_date" class="form-label">Date of Vendor Movement.</label>
                        <input type="text" readonly name="vendor_movement_date" class="form-control flatpickr-date vendor_movement_date" value="{{ request('vendor_movement_date') }}" placeholder="Select date" autocomplete="off" readonly>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="file_barcode" class="form-label">File barcode againt Lot No.</label>
                        <input type="text" name="file_barcode" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="box_barcode" class="form-label">Box Barcode.</label>
                        <input type="text" name="box_barcode" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="date_added_to_vendor" class="form-label">Date of addition to Vendor.</label>
                        <input type="text" readonly name="date_added_to_vendor" class="form-control flatpickr-date date_added_to_vendor" value="{{ request('vendor_movement_date') }}"  placeholder="Select date" autocomplete="off" readonly>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Status</label>
                        <input type="hidden" name="id">
                        <input type="hidden" name="type">
                        <select name="status" class="form-control select2" required>
                            <option value=''>Select Status</option>
                            <option value='8'>In</option>
                            <option value='9'>Out</option>
                            <option value='10'>Permout</option>
                            <option value='11'>Destroyed</option>
                        </select>
                    </div>
                </div>
                {{-- <div class="modal-body">
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
                </div> --}}
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
        flatpickr(".flatpickr-date", {
        dateFormat: "Y-m-d",
        maxDate: "today",         
        allowInput: false,         
        clickOpens: true
    });
        $('#doc-retrive').validate({
            rules: {
                lot_no: {
                    required: true,
                    alphanumeric: true,
                    sanitize: true
                },
                work_order_no: {
                    required: true,
                    alphanumeric: true,
                    sanitize: true
                },
                vendor_name: {
                    required: true,
                    alphanumeric: true,
                    sanitize: true
                },
                vendor_movement_date: {
                    required: true,
                    sanitize: true
                },
                file_barcode: {
                    required: true,
                    alphanumeric: true,
                    sanitize: true
                },
                box_barcode: {
                    required: true,
                    alphanumeric: true,
                    sanitize: true
                },
                status: {
                    required: true,
                    sanitize: true
                }
            },
            submitHandler: function (form) {

                let documentTypes = ['loan', 'goldloan', 'aof', 'dtrf'];
                let hasSelection = false;

                $('#doc-retrive').find('input[name$="_ids[]"]').remove();

                documentTypes.forEach(function (type) {
                    let ids = [];

                    $('input.' + type + ':checked').each(function () {
                        ids.push($(this).data('id'));
                    });

                    if (ids.length > 0) {
                        hasSelection = true;

                        ids.forEach(function (id) {
                            $('#doc-retrive').append(
                                '<input type="hidden" name="' + type + '_ids[]" value="' + id + '">'
                            );
                        });
                    }
                });

                $('#doc-retrive').submit();
            }
            // rules: {
            //     status: {
            //         required: true,
            //         sanitize: true
            //     }
            // }
        });
    });
</script>

@endsection


