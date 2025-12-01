@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="d-flex page-heading">
                <h3 >{{ ucfirst($dispatch->statusName->name) }} Documents</h3>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col">
            <div class="filter-bg">
                <div class="row">
                    <div class="col border-end">
                        <label>Dispatch Number</label>
                        <h5> {{ $dispatch->dispatch_no != null ? $dispatch->dispatch_no : '-' }} </h5>
                    </div>
                    <div class="col border-end">
                        <label>AWB/POD Number</label>
                        <h5> {{ $dispatch->awb_pod != null ? $dispatch->awb_pod : '-' }} </h5>
                    </div>
                    <div class="col border-end">
                        <label>Courier Name</label>
                        <h5> {{ $dispatch->courier_name != null ? $dispatch->courierName->name : '-' }} </h5>
                    </div>
                    <div class="col border-end">
                        <label>MMRP Internal Barcode No.</label>
                        <h5> {{ $dispatch->mmrp_barcode != null ? $dispatch->mmrp_barcode : '-' }} </h5>
                    </div>
                    <div class="col">
                        <label>Dispatch Date</label>
                        <h5>{{ $dispatch->dispatch_date != null ? date('d-m-Y', strtotime($dispatch->dispatch_date)): '-' }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? 'loan') == 'loan' ? 'active':''}}" id="loan" data-bs-toggle="tab" data-bs-target="#loan-pane" type="button" role="tab" aria-controls="loan-pane" aria-selected="true">MB Loan Docs <span class="badge text-bg-warning">{{$loan_total}}</span></button>
                </li>
               
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'goldloan' ? 'active':''}}" id="goldloan" data-bs-toggle="tab" data-bs-target="#goldloan-pane" type="button" role="tab" aria-controls="goldloan-pane" aria-selected="false">Gold Loan Docs <span class="badge text-bg-warning">{{$gold_loan_total}}</span></button>
                </li>
                
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'aof' ? 'active':''}}" id="aof" data-bs-toggle="tab" data-bs-target="#aof-pane" type="button" role="tab" aria-controls="aof-pane" aria-selected="false">Liabilities Docs <span class="badge text-bg-warning">{{$aof_total}}</span></button>
                </li>
                
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'dtrf' ? 'active':''}}" id="dtrf" data-bs-toggle="tab" data-bs-target="#dtrf-pane" type="button" role="tab" aria-controls="dtrf-pane" aria-selected="false">DTR Files <span class="badge text-bg-warning">{{$dtrf_total}}</span></button>
                </li>                    
                <li class="ms-auto">
                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                        @if ($dispatch->status == 5 || $dispatch->status == 7)
                            <button id="update-all" class="btn btn-primary d-none">Update All</button>
                        @endif 
                    @endunless
                    <a href="{{ route('dispatches', $type) }}" class="btn btn-secondary">Back</a>
                    {{-- <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a> --}}
                </li>
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade {{($dtype ?? 'loan') == 'loan' ? 'show active':''}}" id="loan-pane" role="tabpanel" aria-labelledby="loan" tabindex="0">
                    @if(isset($loan_document) && $loan_document->count())
                        {{ $loan_document->links('pagination::bootstrap-5') }}
                    @endif
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
                                    <th scope="col" class="text-nowrap">Customer Name</th>
                                    <th scope="col" class="text-nowrap">Disb Date</th>
                                    <th scope="col" class="text-nowrap">Channel</th>
                                    <th scope="col" class="text-nowrap">Loan Amount</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Glow App ID</th>
                                    <th scope="col" class="text-nowrap">Disb Type</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))                                    
                                        @if ($dispatch->status == 5 || $dispatch->status == 7)
                                            <th class="d-none loan text-nowrap" scope="col">Update Status</th>
                                            <th class="d-none loan text-nowrap" scope="col">Actions</th>
                                        @endif
                                    @endunless
                                </tr>
                            </thead>
                            <tbody>
                                @if ($loan_document)
                                    @foreach ($loan_document as $row)
                                        <tr @if ($row->status == 4) data-id="{{ $row->id }}" data-uid="{{ $row->unique_ref_no }}" data-type="loan" @endif>
                                            <td class="text-nowrap">{{ $row->unique_ref_no }}</td>
                                            <td class="text-nowrap">{{ $row->branch_code }}</td>
                                            <td class="text-nowrap">{{ $row->branch_name }}</td>
                                            <td class="text-nowrap">{{ $row->cif_id }}</td>
                                            <td class="text-nowrap">{{ $row->account_number }}</td>
                                            <td class="text-nowrap">{{ $row->loan_cycle }}</td>
                                            <td class="text-nowrap">{{ $row->customer_name }}</td>
                                            <td class="text-nowrap">{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td class="text-nowrap">{{ $row->channel }}</td>
                                            <td class="text-nowrap">{{ $row->loan_amount }}</td>
                                            <td class="text-nowrap">{{ $row->barcode }}</td>
                                            <td class="text-nowrap">{{ $row->glow_application_id }}</td>
                                            <td class="text-nowrap">{{ $row->loan_disbursement_type }}</td>
                                            <td class="text-nowrap">{{ $row->business_category }}</td>
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td> Received
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>{{ $row->statusName->name ?? '-' }}
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td class="text-nowrap">{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'ro-officer', 'ro-supervisor', 'ho-user', 'branch-user', 'ro-user']))
                                                @if ($row->status == 3)
                                                    <td class="text-nowrap" class="border-start">
                                                        <input type="hidden" name="dispatch_id" value="{{ $dispatch->id ?? '' }}">
                                                        <button data-id="{{ $row->id }}" data-type="loan" class="btn btn-danger remove-doc"> Remove </button>
                                                    </td>
                                                @endif
                                            @endunless
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))                                             
                                                @if ($dispatch->status == 5 || $dispatch->status == 7)
                                                    @if ($row->status == 4)
                                                        <td class="loan text-nowrap">
                                                            <select name="remarks" class="form-control select2 remarks" required>
                                                                <option selected value=5>Received</option>
                                                                <option value=7>Received with Query</option>
                                                                <option value=6>Rejected</option>
                                                            </select>
                                                            <textarea placeholder="Mention the Reason here..." name="reason_for_rejection" class="form-control reason d-none alphanumeric" rows="2"></textarea>
                                                        </td>
                                                        <td class="border-start">
                                                                <button type="button" class="btn btn-primary update-row">Update</button>
                                                        </td>
                                                    @endif
                                                @endif
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if(isset($loan_document) && $loan_document->count())
                        {{ $loan_document->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-muted" style="border-bottom: 1px solid #d8d3d3">No documents found.</p>
                    @endif
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'goldloan' ? 'show active':''}}" id="goldloan-pane" role="tabpanel" aria-labelledby="goldloan" tabindex="0">
                    @if(isset($gold_loan_document) && $gold_loan_document->count())
                        {{ $gold_loan_document->links('pagination::bootstrap-5') }}
                    @endif
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
                                    <th scope="col" class="text-nowrap">Status</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))   
                                        @if ($dispatch->status == 5 || $dispatch->status == 7)
                                            <th class="d-none goldloan text-nowrap" scope="col">Update Status</th>
                                            <th class="d-none goldloan text-nowrap" scope="col">Actions</th>
                                        @endif
                                    @endunless
                                </tr>
                            </thead>
                            <tbody>
                                @if ($gold_loan_document)
                                    @foreach ($gold_loan_document as $row)
                                        <tr @if ($row->status == 4) data-id="{{ $row->id }}" data-uid="{{ $row->unique_ref_no }}" data-type="goldloan" @endif>
                                            <td class="text-nowrap">{{ $row->unique_ref_no }}</td>  
                                            <td class="text-nowrap">{{ $row->branch_code }}</td>
                                            <td class="text-nowrap">{{ $row->branch_name }}</td>
                                            <td class="text-nowrap">{{ $row->cif_id }}</td>
                                            <td class="text-nowrap">{{ $row->account_number }}</td>
                                            <td class="text-nowrap">{{ $row->customer_name }}</td>
                                            <td class="text-nowrap">{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td class="text-nowrap">{{ $row->channel }}</td>
                                            <td class="text-nowrap">{{ $row->loan_amount }}</td>
                                            <td class="text-nowrap">{{ $row->barcode }}</td>
                                            <td class="text-nowrap">{{ $row->business_category }}</td> 
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td class="text-nowrap"> Received
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>{{ $row->statusName->name ?? '-' }}
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td class="text-nowrap">{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'ro-officer', 'ro-supervisor', 'ho-user', 'branch-user', 'ro-user']))
                                                @if ($row->status == 3)
                                                    <td class="text-nowrap" class="border-start">
                                                        <button data-id="{{ $row->id }}" data-type="goldloan" class="btn btn-danger remove-doc"> Remove </button>
                                                    </td>
                                                @endif
                                            @endunless
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))                                             
                                                @if ($dispatch->status == 5 || $dispatch->status == 7)
                                                    @if ($row->status == 4)
                                                        <td class="goldloan text-nowrap">
                                                            <select name="remarks" class="form-control select2 remarks" required>
                                                                <option selected value=5>Received</option>
                                                                <option value=7>Received with Query</option>
                                                                <option value=6>Rejected</option>
                                                            </select>
                                                            <textarea placeholder="Mention the Reason here..." name="reason_for_rejection" class="form-control text-nowrap reason d-none alphanumeric" rows="2"></textarea>
                                                        </td>
                                                        <td class="border-start">
                                                            <button type="button" class="btn btn-primary update-row">Update</button>
                                                        </td>
                                                    @endif
                                                @endif
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if(isset($gold_loan_document) && $gold_loan_document->count())
                            {{ $gold_loan_document->links('pagination::bootstrap-5') }}
                        @else
                            <p class="text-center text-muted" style="border-bottom: 1px solid #d8d3d3">No documents found.</p>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'aof' ? 'show active':''}}" id="aof-pane" role="tabpanel" aria-labelledby="aof" tabindex="0">
                    @if(isset($account_opening_document) && $account_opening_document->count())
                        {{ $account_opening_document->links('pagination::bootstrap-5') }}
                    @endif
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
                                    {{-- <th scope="col" class="text-nowrap">Barcode</th> --}}
                                    <th scope="col" class="text-nowrap">PGK No</th>
                                    <th scope="col" class="text-nowrap">Type</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))                                   
                                        @if ($dispatch->status == 5 || $dispatch->status == 7)
                                            <th class="d-none aof text-nowrap" scope="col">Update Status</th>
                                            <th class="d-none aof text-nowrap" scope="col">Actions</th>
                                        @endif
                                    @endunless
                                </tr>
                            </thead>
                            <tbody>
                                @if ($account_opening_document)
                                    @foreach ($account_opening_document as $row)
                                        <tr @if ($row->status == 4) data-id="{{ $row->id }}" data-uid="{{ $row->unique_ref_no }}" data-type="aof" @endif>
                                            <td class="text-nowrap">{{ $row->unique_ref_no }}</td>
                                            <td class="text-nowrap">{{ $row->branch_code }}</td>
                                            <td class="text-nowrap">{{ $row->branch_name }}</td>
                                            <td class="text-nowrap">{{ $row->cif_id }}</td>
                                            <td class="text-nowrap">{{ $row->account_number }}</td>
                                            <td class="text-nowrap">{{ $row->customer_name }}</td>
                                            <td class="text-nowrap">{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td class="text-nowrap">{{ $row->channel }}</td>
                                            <td class="text-nowrap">{{ $row->scheme }}</td>
                                            {{-- <td class="text-nowrap">{{ $row->barcode }}</td> --}}
                                            <td class="text-nowrap">{{ $row->pgk_no }}</td>
                                            <td class="text-nowrap">{{ $row->type_of_account_opening }}</td>
                                            <td class="text-nowrap">{{ $row->business_category }}</td>
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td class="text-nowrap"> Received
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>{{ $row->statusName->name ?? '-' }}
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td class="text-nowrap">{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'ro-officer', 'ro-supervisor', 'ho-user', 'branch-user', 'ro-user']))
                                                @if ($row->status == 3)
                                                    <td class="text-nowrap" class="border-start">
                                                        <button data-id="{{ $row->id }}" data-type="aof" class="btn btn-danger remove-doc"> Remove </button>
                                                    </td>
                                                @endif
                                            @endunless
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))                                             
                                                @if ($dispatch->status == 5 || $dispatch->status == 7)
                                                    @if ($row->status == 4)
                                                        <td class="aof text-nowrap">
                                                            <select name="remarks" class="form-control select2 remarks" required>
                                                                <option selected value=5>Received</option>
                                                                <option value=7>Received with Query</option>
                                                                <option value=6>Rejected</option>
                                                            </select>
                                                            <textarea placeholder="Mention the Reason here..." name="reason_for_rejection" class="form-control reason d-none alphanumeric" rows="2"></textarea>
                                                        </td>
                                                        <td class="border-start">
                                                            <button type="button" class="btn btn-primary update-row">Update</button>
                                                        </td>
                                                    @endif
                                                @endif
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if(isset($account_opening_document) && $account_opening_document->count())
                            {{ $account_opening_document->links('pagination::bootstrap-5') }}
                        @else
                            <p class="text-center text-muted" style="border-bottom: 1px solid #d8d3d3">No documents found.</p>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'dtrf' ? 'show active':''}}" id="dtrf-pane" role="tabpanel" aria-labelledby="dtrf" tabindex="0">
                    @if(isset($dtrf_document) && $dtrf_document->count())
                        {{ $dtrf_document->links('pagination::bootstrap-5') }}
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    <th scope="col" class="text-nowrap">DTR File Date</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))                                   
                                        @if ($dispatch->status == 5 || $dispatch->status == 7)
                                            <th class="d-none dtrf text-nowrap" scope="col">Update Status</th>
                                            <th class="d-none dtrf text-nowrap" scope="col">Actions</th>
                                        @endif
                                    @endunless
                                </tr>
                            </thead>
                            <tbody>
                                @if ($dtrf_document)
                                    @foreach ($dtrf_document as $row)
                                        <tr @if ($row->status == 4) data-id="{{ $row->id }}" data-uid="{{ $row->unique_ref_no }}" data-type="dtrf" @endif>
                                            <td class="text-nowrap">{{ $row->unique_ref_no }}</td>
                                            <td class="text-nowrap">{{ $row->branch_code }}</td>
                                            <td class="text-nowrap">{{ $row->branch_name }}</td>
                                            <td class="text-nowrap">{{ date('d-m-Y', strtotime($row->account_creation_date))}}</td>
                                            <td class="text-nowrap">{{ $row->barcode}}</td>
                                            <td class="text-nowrap">{{ $row->business_category}}</td>
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td class="text-nowrap"> Received
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>{{ $row->statusName->name ?? '-' }}
                                                        @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td class="text-nowrap">{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'ro-officer', 'ro-supervisor', 'ho-user', 'branch-user', 'ro-user']))                                                 
                                                @if ($row->status == 3)
                                                    <td class="text-nowrap" class="border-start">
                                                        <button data-id="{{ $row->id }}" data-type="dtrf" class="btn btn-danger remove-doc"> Remove </button>
                                                    </td>
                                                @endif
                                            @endunless
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user']))                                             
                                                @if ($dispatch->status == 5 || $dispatch->status == 7)
                                                    @if ($row->status == 4)
                                                        <td class="dtrf text-nowrap">
                                                            <select name="remarks" class="form-control select2 remarks" required>
                                                                <option selected value=5>Received</option>
                                                                <option value=7>Received with Query</option>
                                                                <option value=6>Rejected</option>
                                                            </select>
                                                            <textarea placeholder="Mention the Reason here..." name="reason_for_rejection" class="form-control reason d-none alphanumeric" rows="2"></textarea>
                                                        </td>
                                                        <td class="border-start">
                                                            <button type="button" class="btn btn-primary update-row">Update</button>
                                                        </td>
                                                    @endif
                                                @endif
                                            @endunless
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if(isset($dtrf_document) && $dtrf_document->count())
                            {{ $dtrf_document->links('pagination::bootstrap-5') }}
                        @else
                            <p class="text-center text-muted" style="border-bottom: 1px solid #d8d3d3">No documents found.</p>
                        @endif
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
            <div class="row">
                <div class="col-12 mt-3">
                    <select class="form-select document_type" name="document_type">
                        <option value="">Select Document Type</option>
                        <option value="loan" {{ ($filters['document_type'] ?? '') == 'loan' ? 'selected' : '' }}>MB Loan Documents</option>
                        <option value="goldloan" {{ ($filters['document_type'] ?? '') == 'goldloan' ? 'selected' : '' }}>Gold Loan Documents</option>
                        <option value="aof" {{ ($filters['document_type'] ?? '') == 'aof' ? 'selected' : '' }}>Liabilities Documents</option>
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
                    <a  href="{{ route('accounts.index',['type' => 'all','dtype' => 'loan']) }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="add-vendor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="update-courier" action="{{ route('dispatches.update')}}" method="POST">
                @csrf
                <input type="hidden" name="dispatch_id" value="{{ $dispatch->id }}" autocomplete="off">
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Add Vendor Movement Information</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-2">
                        <label for="tracked_by" class="form-label">Lot No.</label>
                        <input type="number" name="lot_no" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="tracked_by" class="form-label">Work Order No.</label>
                        <input type="number" name="lot_no" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="tracked_by" class="form-label">Vendor Name</label>
                        <input type="text" name="vendor_name" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_movement_date" class="form-label">Date of Vendor Movement.</label>
                        <input type="text" readonly name="vendor_movement_date" class="form-control datepicker vendor_movement_date" value="{{ request('vendor_movement_date') }}">
                        {{-- <input type="date" name="vendor_movement_date" class="form-control vendor_movement_date" value="{{ request('vendor_movement_date') }}"> --}}
                    </div>
                    <div class="col-4 pb-2">
                        <label for="tracked_by" class="form-label">File barcode againt Lot No.</label>
                        <input type="file" name="barcode_file" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="tracked_by" class="form-label">Box Barcode.</label>
                        <input type="text" name="vendor_name" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_movement_date" class="form-label">Date of addition vendor Data</label>
                        <input type="text" readonly name="vendor_movement_date" class="form-control datepicker vendor_movement_date" value="{{ request('vendor_movement_date') }}">
                        {{-- <input type="date" name="vendor_movement_date" class="form-control vendor_movement_date" value="{{ request('vendor_movement_date') }}"> --}}
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control select2" required>
                            <option value=''>Select</option>
                            <option value='In'>In</option>
                            <option value='Out'>Out</option>
                            <option value='Permout'>Permout</option>
                            <option value='Destroyed'>Destroyed</option>
                        </select>
                        {{-- <textarea name="remarks" class="form-control" rows="2"></textarea>x --}}
                    </div>
                    <div class="col-12 pb-2 d-none ">
                        <label for="reason_for_rejection" class="form-label">Reason for Rejection</label>
                        <textarea name="reason_for_rejection" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    {{-- <a href="/accounts-process" class="btn btn-primary btn-lg"><strong>Submit</strong></a> --}}
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var count = $('select[name="remarks"]').length;
        var loancount = $('td.loan').length;
        var goldloancount = $('td.goldloan').length;
        var aofcount = $('td.aof').length;
        var dtrfcount = $('td.dtrf').length;
        
        if(count > 0){
            $('#update-all').removeClass('d-none');
        }
        if(loancount > 0){
            $('th.loan').removeClass('d-none');
        }
        if(goldloancount > 0){
            $('th.goldloan').removeClass('d-none');
        }
        if(aofcount > 0){
            $('th.aof').removeClass('d-none');
        }
        if(dtrfcount > 0){
            $('th.dtrf').removeClass('d-none');
        }
        $('select[name="remarks"]').change(function () {
            const row = $(this).closest('tr');
            const reasonField = row.find('textarea[name="reason_for_rejection"]');

            if ($(this).val() === '6' || $(this).val() === '7') {
                reasonField.removeClass('d-none');
            } else {
                reasonField.addClass('d-none').val('');
            }
        });
        function collectRowData(row) {
            const id = row.data('id');
            const uid = row.data('uid');
            const type = row.data('type');
            const remarks = row.find('.remarks').val();
            const reason = row.find('.reason').val();

            if (remarks !== '5' && !reason.trim()) {
                throw `Reason is required for Document #${uid} under ${type.toUpperCase()}`;
            }

            return { id, type, remarks, reason_for_rejection: reason };
        }

        $('.remove-doc').click(function (e) {
            e.preventDefault();
            $(this).prop('disabled', true);
            let docId = $(this).data('id');
            let type = $(this).data('type');
            let dispatchId = $('input[name="dispatch_id"]').val(); // must be present as hidden input
            let row = $(this).closest('tr');

            var doc_count = $(`#${type}`).find('span.badge').text();
            // console.log(doc_count);


            if (!docId || !type || !dispatchId) {
                Swal.fire({title: "Warning!", text: "Missing document data.", icon: "warning"});
                return;
            }

            Swal.fire({
                title: "Confirm Removal",
                text: "Are you sure you want to remove this document?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, remove",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`{{ route('document.dispatchremove') }}`, {
                        _token: $('input[name="_token"]').val(),
                        doc_id: docId,
                        type: type,
                        dispatch_id: dispatchId,
                    })
                    .done(function () {
                        Swal.fire({
                            title: "Removed!",
                            text: "Document removed successfully.",
                            icon: "success",
                            timer: 1000,
                            showConfirmButton: false
                        });
                        row.remove(); 
                        $(`#${type}`).find('span.badge').text(doc_count - 1);
                    })
                    .fail(function (xhr) {
                        Swal.fire({title: "Error!", text: "Something went wrong: " + xhr.responseText, icon: "error"});
                    });
                }
            });
        });


        $('.update-row').on('click', function () {
            $(this).prop('disabled', true);
            const row = $(this).closest('tr');
            let data;

            try {
                data = [collectRowData(row)];
            } catch (err) {
                // Swal.fire({title: "Alert!", text: err, icon: "warning"});
                Swal.fire({title: "Alert!", text: err, icon: "warning"});
                return;
            }

            sendUpdateRequest(data,'row');
        });

        // Handle bulk update
        $('#update-all').on('click', function () {
            const activeTab = $('.nav-link.active').attr('id'); // e.g., "loan"
            const data = [];
            let hasError = false;

            let tabSelector = '';

            // Map tab id to row class or pane
            switch (activeTab) {
                case 'loan':
                    tabSelector = '#loan-pane';
                    break;
                case 'goldloan':
                    tabSelector = '#goldloan-pane';
                    break;
                case 'aof':
                    tabSelector = '#aof-pane';
                    break;
                case 'dtrf':
                    tabSelector = '#dtrf-pane';
                    break;
            }

            $(`${tabSelector} tr[data-id]`).each(function () {
                try {
                    data.push(collectRowData($(this)));
                } catch (err) {
                    Swal.fire({title: "Alert!", text: err, icon: "warning"});
                    hasError = true;
                    return false; // stop loop
                }
            });

            if (!hasError && data.length) {
                sendUpdateRequest(data,'all');
            }
        });

        function sendUpdateRequest(payload, type) {
        console.log(payload);
        // return false;
            $.ajax({
                url: '{{ route("document.update") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    updates: payload
                },
                success: function () {
                    if (type === 'all') { 
                        Swal.fire({title: "Success" , text:  "Update successful", icon: "success"}).then(() => location.reload());
                    } else {
                        location.reload(); 
                    }
                },
                error: function () {
                    Swal.fire({title: "Error!", text: "Update failed!", icon: "error"});
                }
            });
        }
    });
</script>

@endsection


