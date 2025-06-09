@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="d-flex page-heading">
                <h3 >{{ ucfirst($type) }} Documents</h3>
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
                    <button class="nav-link {{($filters['document_type'] ?? 'loan') == 'loan' ? 'active':''}}" id="loanac-tab" data-bs-toggle="tab" data-bs-target="#loanac-tab-pane" type="button" role="tab" aria-controls="loanac-tab-pane" aria-selected="true">
                        MB Loan Documents <span class="badge text-bg-warning">{{ $loan_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'gold_loan' ? 'active':''}}" id="goldloan-tab" data-bs-toggle="tab" data-bs-target="#goldloan-tab-pane" type="button" role="tab" aria-controls="goldloan-tab-pane" aria-selected="false">
                        Gold Loan Documents <span class="badge text-bg-warning">{{ $gold_loan_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'aof' ? 'active':''}}" id="aof-tab" data-bs-toggle="tab" data-bs-target="#aof-tab-pane" type="button" role="tab" aria-controls="aof-tab-pane" aria-selected="false">
                        Liablities Documents <span class="badge text-bg-warning">{{ $aof_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'dtrf' ? 'active':''}}" id="dtrf-tab" data-bs-toggle="tab" data-bs-target="#dtrf-tab-pane" type="button" role="tab" aria-controls="dtrf-tab-pane" aria-selected="false">
                        DTR Files <span class="badge text-bg-warning">{{ $dtrf_total }}</span>
                    </button>
                </li>
                @hasanyrole('master|bo-maker|bo-checker')
                <li class="ms-auto">
                    <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed">
                        @csrf
                        <button class="btn btn-primary proceed" type="button">Proceed</button>
                    </form>
                </li>
                @endhasanyrole
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade {{($filters['document_type'] ?? 'loan') == 'loan' ? 'show active':''}}" id="loanac-tab-pane" role="tabpanel" aria-labelledby="loanac-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasanyrole('master|bo-maker|bo-checker')
                                <th scope="col"><input type="checkbox" class="loan_all" /> </th>
                                @endhasanyrole
                                <th scope="col">Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col">Region</th>
                                <th scope="col">Branch Name</th>
                                @endunless
                                <th scope="col">Branch Code</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">Account Number</th>
                                <th scope="col">Loan Cycle</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Account Creation Date</th>
                                <th scope="col">Channel</th>
                                {{-- <th scope="col">Barcode</th> --}}
                                {{-- <th scope="col">Glow application ID<br>/Barcode</th> --}}
                                <th scope="col">Type of Loan<br>Disbursement</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                {{-- <th scope="col" class="border-start">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($loan_document)
                                @foreach ($loan_document as $row)
                                    <tr>
                                        @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1)
                                            <input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        <td>{{ $row->unique_ref_no }}</td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        @endunless
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->loan_cycle }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->account_creation_date }}</td>
                                        <td>{{ $row->channel }}</td>
                                        {{-- <td>{{ $row->barcode }}</td> --}}
                                        {{-- <td>{{ $row->barcode }}</td> --}}
                                        <td>{{ $row->loan_disbursement_type }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        {{-- <td>{{ ucwords(str_replace("_"," ",$row->business_type)) }}</td>
                                        <td>{{ ucwords(str_replace("-"," ",$row->rbi_classification)) }}</td> --}}
                                        {{-- <td>{{ $row->branch_office_type }}</td>
                                        <td>{{ $row->pincode }}</td>
                                        <td>{{ $row->city }}</td> --}}
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if(isset($loan_document) && $loan_document->count())
                        {{ $loan_document->links('pagination::bootstrap-5') }}
                    @endif
                </div>
                <div class="tab-pane fade {{($filters['document_type'] ?? '') == 'gold_loan' ? 'show active':''}}" id="goldloan-tab-pane" role="tabpanel" aria-labelledby="goldloan-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasanyrole('master|bo-maker|bo-checker')
                                <th scope="col"><input type="checkbox" class="goldloan_all"/> </th>
                                @endhasanyrole
                                <th scope="col">Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col">Region</th>
                                <th scope="col">Branch Name</th>
                                @endunless
                                <th scope="col">Branch Code</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">Account Number</th>
                                {{-- <th scope="col">Loan Cycle</th> --}}
                                <th scope="col">Customer Name</th>
                                <th scope="col">Account Creation Date</th>
                                <th scope="col">Channel</th>
                                {{-- <th scope="col">Glow application ID<br>/Barcode</th> --}}
                                {{-- <th scope="col">Barcode</th> --}}
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                {{-- <th scope="col" class="border-start">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($gold_loan_document)
                                @foreach ($gold_loan_document as $row)
                                    <tr>
                                        @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1)
                                                <input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        <td>{{ $row->unique_ref_no }}</td> 
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        @endunless
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->account_creation_date }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td>{{ $row->business_category }}</td> 
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        {{-- <td class="border-start">
                                            <div class="btn-actions">
                                                <a href="{{ route('accounts.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                                {{-- <form action="{{ route('accounts.destroy', $row->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                                </form> --}}
                                            {{-- </div>
                                        </td> --}} 
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if(isset($gold_loan_document) && $gold_loan_document->count())
                        {{ $gold_loan_document->links('pagination::bootstrap-5') }}
                    @endif
                </div>
                <div class="tab-pane fade {{($filters['document_type'] ?? '') == 'aof' ? 'show active':''}}" id="aof-tab-pane" role="tabpanel" aria-labelledby="aof-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasanyrole('master|bo-maker|bo-checker')
                                <th scope="col"><input type="checkbox" class="aof_all" /> </th>
                                @endhasanyrole
                                <th scope="col">Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col">Region</th>
                                <th scope="col">Branch Name</th>
                                @endunless
                                <th scope="col">Branch Code</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">Account Number</th>
                                {{-- <th scope="col">Loan Cycle</th> --}}
                                <th scope="col">Customer Name</th>
                                <th scope="col">Account Creation Date</th>
                                <th scope="col">Channel</th>
                                {{-- <th scope="col">Barcode</th> --}}
                                {{-- <th scope="col">Glow application ID<br>/Barcode</th> --}}
                                <th scope="col">Type of Account Opening</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                {{-- <th scope="col" class="border-start">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($account_opening_document)
                                @foreach ($account_opening_document as $row)
                                    <tr>
                                        @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1)
                                                <input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        {{-- <td>{{ $loop->iteration }}</td> --}}
                                        {{-- <td><input type="checkbox" /></td> --}}
                                        <td>{{ $row->unique_ref_no }}</td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        @endunless
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        {{-- <td>{{ $row->loan_cycle }}</td> --}}
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->account_creation_date }}</td>
                                        <td>{{ $row->channel }}</td>        
                                        {{-- <td>{{ $row->barcode }}</td> --}}
                                        <td>{{ $row->type_of_account_opening }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        {{-- <td>{{ ucwords(str_replace("_"," ",$row->business_type)) }}</td>
                                        <td>{{ ucwords(str_replace("-"," ",$row->rbi_classification)) }}</td> --}}
                                        {{-- <td>{{ $row->branch_office_type }}</td>
                                        <td>{{ $row->pincode }}</td>
                                        <td>{{ $row->city }}</td> --}}
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if(isset($account_opening_document) && $account_opening_document->count())
                        {{ $account_opening_document->links('pagination::bootstrap-5') }}
                    @endif
                </div>
                <div class="tab-pane fade {{($filters['document_type'] ?? '') == 'dtrf' ? 'show active':''}}" id="dtrf-tab-pane" role="tabpanel" aria-labelledby="dtrf-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasanyrole('master|bo-maker|bo-checker')
                                <th scope="col"><input type="checkbox" class="dtrf_all"/> </th>
                                @endhasanyrole
                                <th scope="col">Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col">Region</th>
                                <th scope="col">Branch Name</th>
                                @endunless
                                <th scope="col">Branch Code</th>
                                <th scope="col">DTR File Date</th>
                                {{-- <th scope="col">barcode</th> --}}
                                {{-- <th scope="col">Loan Cycle</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Account Creation Date</th>
                                <th scope="col">Channel</th>
                                {{-- <th scope="col">Glow application ID<br>/Barcode</th> --}}
                                {{-- <th scope="col">Type of Loan<br>Disbursement</th>  --}}
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                {{-- <th scope="col" class="border-start">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dtrf_document)
                                @foreach ($dtrf_document as $row)
                                    <tr>
                                        @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1)
                                                <input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        {{-- <td>{{ $loop->iteration }}</td> --}}
                                        {{-- <td><input type="checkbox" /></td> --}}
                                        <td>{{ $row->unique_ref_no }}</td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        @endunless
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->account_creation_date}}</td>
                                        {{-- <td>{{ $row->barcode }}</td> --}}
                                        <td>{{ $row->business_category}}</td>
                                        {{-- <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->account_creation_date }}</td>
                                        <td>{{ $row->channel }}</td>
                                        {{-- <td>{{ $row->barcode }}</td> --}}
                                        {{-- <td>{{ $row->loan_disbursement_type }}</td>
                                        <td>{{ $row->business_category }}</td> --}} 
                                        {{-- <td>{{ ucwords(str_replace("_"," ",$row->business_type)) }}</td>
                                        <td>{{ ucwords(str_replace("-"," ",$row->rbi_classification)) }}</td> --}}
                                        {{-- <td>{{ $row->branch_office_type }}</td>
                                        <td>{{ $row->pincode }}</td>
                                        <td>{{ $row->city }}</td> --}}
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                @endif
                        </tbody>
                    </table>
                    @if(isset($dtrf_document) && $dtrf_document->count())
                        {{ $dtrf_document->links('pagination::bootstrap-5') }}
                    @endif
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
                    <input type="text" class="form-control account_number" placeholder="Account Number" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
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
                    <input type="text" readonly class="form-control datepicker" placeholder="From Date" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control datepicker" placeholder="To Date" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            {{-- <form id="update-courier" action="/accounts-update" method="POST"> --}}
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary">Update Details</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-4">
                        <label>Unique Number</label>
                        <h5 class="unique_ref_no">UJJ029921</h5>
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
                        <label for="status" class="form-label">Courier Name</label>
                        <input type="text" name="courier_name" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">AWB/POD</label>
                        <input type="text" name="awb_pod" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="dispatch_date" class="form-label">Dispatch Date</label>
                        <input type="text" readonly class="form-control datepicker dispatch_date" value="{{ old('dispatch_date', $filters['dispatch_date'] ?? '') }}" name="dispatch_date" id="dispatch_date" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a href="/accounts-process" class="btn btn-primary btn-lg"><strong>Submit</strong></a>
                    {{-- <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button> --}}
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            {{-- </form> --}}
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        
        $(".loan_all").click(function () {
            $(".loan").prop('checked', $(this).prop('checked'));
        });
        $(".goldloan_all").click(function () {
            $(".goldloan").prop('checked', $(this).prop('checked'));
        });
        $(".aof_all").click(function () {
            $(".aof").prop('checked', $(this).prop('checked'));
        });
        $(".dtrf_all").click(function () {
            $(".dtrf").prop('checked', $(this).prop('checked'));
        });

        
        $('.proceed').click(function () {
            let documentTypes = ['loan', 'goldloan', 'aof', 'dtrf'];
            let hasSelection = false;

            $('#proceed').find('input[name$="_ids[]"]').remove();

            documentTypes.forEach(function (type) {
                let ids = [];

                $('input.' + type + ':checked').each(function () {
                    ids.push($(this).data('id'));
                });

                if (ids.length > 0) {
                    hasSelection = true;

                    ids.forEach(function (id) {
                        $('#proceed').append(
                            '<input type="hidden" name="' + type + '_ids[]" value="' + id + '">'
                        );
                    });
                }
            });

            if (hasSelection) {
                $('#proceed').submit();
            } else {
                Swal.fire({
                    title: "Warning!",
                    text: "Please select at least one Document.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            }
        });

        // Filter Form Validation
        $('form[action="{{ route('document.filter') }}"]').on('submit', function (e) {
            let hasFilter = false;

            
            $(this).find('input:not([type=hidden]):visible, select:visible').each(function () {
                if ($(this).val().trim() !== '') {
                    hasFilter = true;
                    return false; 
                }
            });

            if (!hasFilter) {
                e.preventDefault(); 
                Swal.fire({
                    title: "Warning!",
                    text: "Please select at least one filter option.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            }
        });
    });
</script>


@endsection