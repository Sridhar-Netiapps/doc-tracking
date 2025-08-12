@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col">
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
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                {{-- @if ($loan_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? 'loan') == 'loan' ? 'active':''}} " id="loan" data-bs-toggle="tab" data-bs-target="#loan-pane" type="button" role="tab" aria-controls="loan-pane" aria-selected="true">MB Loan Docs <span class="badge text-bg-warning">{{$loan_document != Null ?count($loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($gold_loan_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'gold_loan' ? 'active':''}}" id="goldloan" data-bs-toggle="tab" data-bs-target="#goldloan-pane" type="button" role="tab" aria-controls="goldloan-pane" aria-selected="false">Gold Loan Docs <span class="badge text-bg-warning">{{$gold_loan_document != Null ?count($gold_loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($account_opening_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'aof' ? 'active':''}}" id="aof" data-bs-toggle="tab" data-bs-target="#aof-pane" type="button" role="tab" aria-controls="aof-pane" aria-selected="false">Liabilities Docs  <span class="badge text-bg-warning">{{$account_opening_document != Null ?count($account_opening_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($dtrf_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'dtrf' ? 'active':''}}" id="dtrf" data-bs-toggle="tab" data-bs-target="#dtrf-pane" type="button" role="tab" aria-controls="dtrf-pane" aria-selected="false">DTR Files <span class="badge text-bg-warning">{{$dtrf_document != Null ?count($dtrf_document):0}}</span></button>
                </li>                    
                {{-- @endif --}}
                @hasanyrole('ro-officer')
                <li class="ms-auto">
                    <button id="update-all" class="btn btn-primary d-none">Update All</button>
                </li>
                @endhasanyrole
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade {{($dtype ?? 'loan') == 'loan' ? 'show active':''}}" id="loan-pane" role="tabpanel" aria-labelledby="loan" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    <th scope="col" class="text-nowrap">CIF ID</th>
                                    <th scope="col" class="text-nowrap">A/C No</th>
                                    <th scope="col" class="text-nowrap">Loan Cycle</th>
                                    <th scope="col" class="text-nowrap">Loan Amount</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Glow App ID</th>
                                    <th scope="col" class="text-nowrap">Customer Name</th>
                                    <th scope="col" class="text-nowrap">Disb Date</th>
                                    <th scope="col" class="text-nowrap">Channel</th>
                                    <th scope="col" class="text-nowrap">Disb Type</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Lot No</th>
                                    <th scope="col" class="text-nowrap">Doc. Category</th>
                                    <th scope="col" class="text-nowrap">Work Order No</th>
                                    <th scope="col" class="text-nowrap">Vendor Name</th>
                                    <th scope="col" class="text-nowrap">Date of Movement</th>
                                    <th scope="col" class="text-nowrap">File Barcode</th>
                                    <th scope="col" class="text-nowrap">Box Barcode</th>
                                    <th scope="col" class="text-nowrap">Date of addition</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                    <th scope="col" class="text-nowrap">Action</th>
                                    @endunless
                                </tr>
                            </thead>
                            <tbody>
                                @if ($loan_document)
                                    @foreach ($loan_document as $row)
                                    <tr class="doc-row"
                                                data-lot_no="{{ $row->lot_no }}"
                                                data-work_order_no="{{ $row->work_order_no }}"
                                                data-file_barcode="{{ $row->file_barcode }}"
                                                data-box_barcode="{{ $row->box_barcode }}">
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
                                            {{-- <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td> --}}
                                            <td>{{ $row->vendor_movement_date ? date('d-m-Y', strtotime($row->vendor_movement_date)) : '-' }}</td>
                                            <td>{{ $row->file_barcode }}</td>
                                            <td>{{ $row->box_barcode }}</td>
                                            <td>{{ $row->date_added_to_vendor ? date('d-m-Y', strtotime($row->date_added_to_vendor)) : '-' }}</td>
                                            <td>{{ $row->statusName->name ?? '-' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                            <td>
                                                @if ($row->status != 11)
                                                <button type="submit" data-id="{{ $row->id }}" data-type="loan" class="btn btn-primary retrive" data-bs-toggle="modal" data-bs-target="#doc-retrive">Update</button>
                                                @endif
                                            </td>
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'goldloan' ? 'show active':''}}" id="goldloan-pane" role="tabpanel" aria-labelledby="goldloan" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    <th scope="col" class="text-nowrap">CIF ID</th>
                                    <th scope="col" class="text-nowrap">A/C No</th>
                                    <th scope="col" class="text-nowrap">Customer Name</th>
                                    <th scope="col" class="text-nowrap">Creation Date</th>
                                    <th scope="col" class="text-nowrap">Channel</th>
                                    <th scope="col" class="text-nowrap">Loan Amount</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Lot No</th>
                                    <th scope="col" class="text-nowrap">Doc. Category</th>
                                    <th scope="col" class="text-nowrap">Work Order No</th>
                                    <th scope="col" class="text-nowrap">Vendor Name</th>
                                    <th scope="col" class="text-nowrap">Date of Movement</th>
                                    <th scope="col" class="text-nowrap">File Barcode</th>
                                    <th scope="col" class="text-nowrap">Box Barcode</th>
                                    <th scope="col" class="text-nowrap">Date of addition</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                    <th scope="col" class="text-nowrap">Action</th>
                                    @endunless
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
                                            <td>{{ $row->vendor_movement_date ? date('d-m-Y', strtotime($row->vendor_movement_date)) : '-' }}</td>
                                            <td>{{ $row->file_barcode }}</td>
                                            <td>{{ $row->box_barcode }}</td>
                                            <td>{{ $row->date_added_to_vendor ? date('d-m-Y', strtotime($row->date_added_to_vendor)) : '-' }}</td>
                                            <td>{{ $row->statusName->name ?? '-' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                            <td>
                                                @if ($row->status != 11)
                                                <button type="submit" data-id="{{ $row->id }}" data-type="goldloan" class="btn btn-primary retrive" data-bs-toggle="modal" data-bs-target="#doc-retrive">Update</button>
                                                @endif
                                            </td>
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'aof' ? 'show active':''}}" id="aof-pane" role="tabpanel" aria-labelledby="aof" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    <th scope="col" class="text-nowrap">CIF ID</th>
                                    <th scope="col" class="text-nowrap">A/C No</th>
                                    <th scope="col" class="text-nowrap">Customer Name</th>
                                    <th scope="col" class="text-nowrap">Creation Date</th>
                                    <th scope="col" class="text-nowrap">Channel</th>
                                    <th scope="col" class="text-nowrap">Scheme</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">PGK No</th>
                                    <th scope="col" class="text-nowrap">Type</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Lot No</th>
                                    <th scope="col" class="text-nowrap">Doc. Category</th>
                                    <th scope="col" class="text-nowrap">Work Order No</th>
                                    <th scope="col" class="text-nowrap">Vendor Name</th>
                                    <th scope="col" class="text-nowrap">Date of Movement</th>
                                    <th scope="col" class="text-nowrap">File Barcode</th>
                                    <th scope="col" class="text-nowrap">Box Barcode</th>
                                    <th scope="col" class="text-nowrap">Date of addition</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                    <th scope="col" class="text-nowrap">Action</th>
                                    @endunless
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
                                            <td>{{ $row->vendor_movement_date ? date('d-m-Y', strtotime($row->vendor_movement_date)) : '-' }}</td>
                                            <td>{{ $row->file_barcode }}</td>
                                            <td>{{ $row->box_barcode }}</td>
                                            <td>{{ $row->date_added_to_vendor ? date('d-m-Y', strtotime($row->date_added_to_vendor)) : '-' }}</td>
                                            <td>{{ $row->statusName->name ?? '-' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                            <td>
                                                @if ($row->status != 11)
                                                <button type="submit" data-id="{{ $row->id }}" data-type="aof" class="btn btn-primary retrive" data-bs-toggle="modal" data-bs-target="#doc-retrive">Update</button>
                                                @endif
                                            </td>
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'dtrf' ? 'show active':''}}" id="dtrf-pane" role="tabpanel" aria-labelledby="dtrf" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    <th scope="col" class="text-nowrap">DTR File Date</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Lot No</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Doc. Category</th>
                                    <th scope="col" class="text-nowrap">Work Order No</th>
                                    <th scope="col" class="text-nowrap">Vendor Name</th>
                                    <th scope="col" class="text-nowrap">Date of Movement</th>
                                    <th scope="col" class="text-nowrap">File Barcode</th>
                                    <th scope="col" class="text-nowrap">Box Barcode</th>
                                    <th scope="col" class="text-nowrap">Date of addition</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                    <th scope="col" class="text-nowrap">Action</th>
                                    @endunless
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
                                            <td>{{ $row->vendor_movement_date ? date('d-m-Y', strtotime($row->vendor_movement_date)) : '-' }}</td>
                                            <td>{{ $row->file_barcode }}</td>
                                            <td>{{ $row->box_barcode }}</td>
                                            <td>{{ $row->date_added_to_vendor ? date('d-m-Y', strtotime($row->date_added_to_vendor)) : '-' }}</td>
                                            <td>{{ $row->statusName->name ?? '-' }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @unless(auth()->user()->hasAnyRole(['ho-user|ro-user']))
                                            <td>
                                                @if ($row->status != 11)
                                                <button type="submit" data-id="{{ $row->id }}" data-type="dtrf" class="btn btn-primary retrive" data-bs-toggle="modal" data-bs-target="#doc-retrive">Update</button>
                                                @endif
                                            </td>
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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
        <form method="POST" action="{{ route('document.filter') }}">
            @csrf
            <input type="hidden" name="type" value="{{$type}}">
            <input type="hidden" name="dtype" value="{{$dtype}}">
            <div class="row">
                <div class="col-12 mt-3">
                    <select class="form-select document_type" name="document_type">
                        <option value="">Select Document Type</option>
                        <option value="loan" {{ ($filters['document_type'] ?? '') == 'loan' ? 'selected' : '' }}>MB Loan Docs</option>
                        <option value="goldloan" {{ ($filters['document_type'] ?? '') == 'goldloan' ? 'selected' : '' }}>Gold Loan Docs</option>
                        <option value="aof" {{ ($filters['document_type'] ?? '') == 'aof' ? 'selected' : '' }}>Liabilities Docs</option>
                        <option value="dtrf" {{ ($filters['document_type'] ?? '') == 'dtrf' ? 'selected' : '' }}>DTR Files</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control unique_ref_no alphanumeric" placeholder="Unique Number" value="{{ old('unique_ref_no', $filters['unique_ref_no'] ?? '') }}" name="unique_ref_no">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ro-officer', 'ro-supervisor']))
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
                    <input type="number" class="form-control branch_code" placeholder="Branch Code" value="{{ old('branch_code', $filters['branch_code'] ?? '') }}" name="branch_code" min="0">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="col-12 mt-3">
                    <input type="text" class="form-control branch_name lettersonly" placeholder="Branch Name" value="{{ old('branch_name', $filters['branch_name'] ?? '') }}" name="branch_name">
                </div>
                @endunless
                <div class="col-12 mt-3">
                    <input type="text" class="form-control cif_id alphanumeric" placeholder="CIF ID" value="{{ old('cif_id', $filters['cif_id'] ?? '') }}" name="cif_id">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control account_number alphanumeric" placeholder="A/C No" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control lettersonly" placeholder="Business Category" value="{{ old('business_category', $filters['business_category'] ?? '') }}" name="business_category">
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="number" class="form-control loan_cycle" placeholder="Loan Cycle" value="{{ old('loan_cycle', $filters['loan_cycle'] ?? '') }}" name="loan_cycle" min=0>
                </div>
                
                <div class="col-12 mt-3">
                    <input type="text" class="form-control category_of_document lettersonly" placeholder="Document Category" value="{{ old('category_of_document', $filters['category_of_document'] ?? '') }}" name="category_of_document">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control work_order_no alphanumeric" placeholder="Work Order No" value="{{ old('work_order_no', $filters['work_order_no'] ?? '') }}" name="work_order_no">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control file_barcode alphanumeric" placeholder="File Barcode" value="{{ old('file_barcode', $filters['file_barcode'] ?? '') }}" name="file_barcode">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control box_barcode alphanumeric" placeholder="Box Barcode" value="{{ old('box_barcode', $filters['box_barcode'] ?? '') }}" name="box_barcode">
                </div>
                <div class="col-12 mt-3 d-none">
                    <select class="form-select scheme" name="scheme">
                        <option value="">Select Scheme</option>
                        <option value="GL" {{ ($filters['scheme'] ?? '') == 'GL' ? 'selected' : '' }}>GL</option>
                        <option value="IL" {{ ($filters['scheme'] ?? '') == 'IL' ? 'selected' : '' }}>IL</option>
                    </select>
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="text" class="form-control customer_name lettersonly" placeholder="Customer Name" value="{{ old('customer_name', $filters['customer_name'] ?? '') }}" name="customer_name">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Date From" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Date To" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
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
                    <a  href="{{ route('accounts.index',['type' => $type,'dtype' => $dtype]) }}" class="btn btn-secondary">Clear</a>
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
                        <label for="lot_no" class="form-label">Lot No</label>
                        <input type="text" id="lot_no_input" name="lot_no" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="category_of_document" class="form-label">Doc. Category</label>
                        <select class="form-select document_type" name="category_of_document" id="category_input" required>
                            <option value="">Select Doc. Category</option>
                            <option value="CAT A1" {{ ($doc->category_of_document ?? '') == 'CAT A1' ? 'selected' : '' }}>CAT A1</option>
                            <option value="CAT A2" {{ ($doc->category_of_document ?? '') == 'CAT A2' ? 'selected' : '' }}>CAT A2</option>
                            <option value="CAT B"  {{ ($doc->category_of_document ?? '') == 'CAT B'  ? 'selected' : '' }}>CAT B</option>
                            <option value="CAT C"  {{ ($doc->category_of_document ?? '') == 'CAT C'  ? 'selected' : '' }}>CAT C</option>
                        </select>                                               
                    </div>
                    <div class="col-4 pb-2">
                        <label for="work_order_no" class="form-label">Work Order No</label>
                        <input type="text" id="work_order_input" name="work_order_no" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_name" class="form-label">Vendor Name</label>
                        <select class="form-select" name="vendor_name" id="vendor_input" required>
                            <option value="">Select Vendor Name</option>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->name }}" {{ ($doc->vendor_name ?? '') == $vendor->name ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>                            
                            @endforeach
                        </select>                                                
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_movement_date" class="form-label">Date of Movement</label>
                        <input type="date" id="vendor_movement_date_input" name="vendor_movement_date" class="form-control flatpickr-date">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="file_barcode" class="form-label">File barcode</label>
                        <input type="text" id="file_barcode_input" name="file_barcode" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="box_barcode" class="form-label">Box Barcode</label>
                        <input type="text" id="box_barcode_input" name="box_barcode" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="date_added_to_vendor" class="form-label">Date of addition</label>
                        <input type="date" id="date_added_input" name="date_added_to_vendor" class="form-control flatpickr-date">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Status</label>
                        <input type="hidden" name="id">
                        <input type="hidden" name="type">
                        <select name="status" class="form-control select2" id="status_input" required>
                            {{-- <option value=''>Select Status</option> --}}
                            <option value='8'>IN</option>
                            <option value='9'>OUT</option>
                            <option value='10'>Permout</option>
                            <option value='11'>Destroyed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg cancel-modal" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        let dtype = '{{$dtype}}';
        let activeTab = null;
        let fallbackTab = null;

        $('button.nav-link').each(function() {
            let tab = $(this).attr('id');
            let count = parseInt($(this).find('span').text()) || 0;

            // Primary choice: dtype matches tab & count > 0
            if (!activeTab && dtype === tab && count > 0) {
                activeTab = tab;
            }

            // Fallback choice: dtype doesn't match tab but count > 0
            if (!fallbackTab && dtype !== tab && count > 0) {
                fallbackTab = tab;
            }
        });

        // Decide final active tab
        if (!activeTab) {
            // If all tabs have > 0, prefer dtype tab
            if ($('button.nav-link').filter(function() {
                return parseInt($(this).find('span').text()) || 0;
            }).length === $('button.nav-link').length) {
                activeTab = dtype;
            } else {
                activeTab = fallbackTab;
            }
        }

        // Activate the selected tab
        if (activeTab) {
            $('button.nav-link, .tab-pane').removeClass('active show');
            $(`#${activeTab}`).addClass('active');
            $(`#${activeTab}-pane`).addClass('show active');
        }

        $('.retrive').click(function () {
            $('input[name="id"]').val($(this).data('id'));
            $('input[name="type"]').val($(this).data('type'));

            $('#retrive').modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');;
        });
        flatpickr(".flatpickr-date", {
            dateFormat: "d-m-Y",        
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
                loan_total
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

        $(document).on('click', '.retrive', function () {
            const id = $(this).data('id');
            const type = $(this).data('type');

            $.ajax({
                url: `/get-document-details/${type}/${id}`,
                type: 'GET',
                success: function (data) {
                    $('#lot_no_input').val(data.lot_no ?? '');
                    $('#work_order_input').val(data.work_order_no ?? '');
                    $('#file_barcode_input').val(data.file_barcode ?? '');
                    $('#box_barcode_input').val(data.box_barcode ?? '');
                    if ($('#category_input option[value="' + data.category_of_document + '"]').length === 0) {
                        $('#category_input').append(new Option(data.category_of_document, data.category_of_document));
                    }
                    $('#category_input').val(data.category_of_document).change();
                    if ($('#vendor_input option[value="' + data.vendor_name + '"]').length === 0) {
                        $('#vendor_input').append(new Option(data.vendor_name, data.vendor_name));
                    }
                    $('#vendor_input').val(data.vendor_name).change();

                    $('#vendor_movement_date_input').val(data.vendor_movement_date ?? '');
                    $('#date_added_input').val(data.date_added_to_vendor ?? '');
                    if ($('#status_input option[value="' + data.status + '"]').length === 0) {
                        $('#status_input').append(new Option(data.status, data.status));
                    }
                    $('#status_input').val(data.status).change();
                    $('#update_id').val(data.id); 
                },
                error: function () {
                    alert('Failed to fetch document data.');
                }
            });
        });
        $('.vendor-upload').click(function () {
            $('#upload-vendor').modal('show');
            // $('#add-vendor').modal('show');
        });
        $(document).ready(function () {
            $('.cancel-modal').on('click', function () {
                $('#yourModalId').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            });
        });
    });
</script>

@endsection


