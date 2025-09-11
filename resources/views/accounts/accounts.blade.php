@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="d-flex page-heading">
                <h3 >{{ ucfirst($type) }} Docs</h3>
                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Filters</button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? 'loan') == 'loan' ? 'active':''}}" id="loan" data-bs-toggle="tab" data-bs-target="#loan-pane" type="button" role="tab" aria-controls="loan-pane" aria-selected="true">
                        MB Loan Docs <span class="badge text-bg-warning">{{ $loan_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'goldloan' ? 'active':''}}" id="goldloan" data-bs-toggle="tab" data-bs-target="#goldloan-pane" type="button" role="tab" aria-controls="goldloan-pane" aria-selected="false">
                        Gold Loan Docs <span class="badge text-bg-warning">{{ $gold_loan_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'aof' ? 'active':''}}" id="aof" data-bs-toggle="tab" data-bs-target="#aof-pane" type="button" role="tab" aria-controls="aof-pane" aria-selected="false">
                        Liabilities Docs <span class="badge text-bg-warning">{{ $aof_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($dtype ?? '') == 'dtrf' ? 'active':''}}" id="dtrf" data-bs-toggle="tab" data-bs-target="#dtrf-pane" type="button" role="tab" aria-controls="dtrf-pane" aria-selected="false">
                        DTR Files <span class="badge text-bg-warning">{{ $dtrf_total }}</span>
                    </button>
                </li>
                @hasanyrole('bo-maker|bo-checker')
                    @if (!in_array($type, ['received']))
                        <li class="ms-auto">
                            <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed">
                                @csrf
                                <button class="btn btn-primary proceed" type="button">Proceed</button>
                            </form>
                        </li>
                    @endif
                @endhasanyrole
                @hasanyrole('master|super_admin|admin')
                    @if (!in_array($type, ['received']))
                        <li>
                            <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed">
                                @csrf
                                <button class="btn btn-primary proceed" type="button">Proceed</button>
                            </form>
                        </li>
                    @endif
                @endhasanyrole

                @role('ro-supervisor|master|super_admin|admin')
                    {{-- @if ($type != 'rejected' && $type != 'pending') --}}
                    {{-- @if ($type !== 'rejected') --}}
                        <li class="ms-auto">
                            {{-- <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed"> --}}
                                {{-- @csrf --}}
                                <button class="btn btn-danger btn-sm remove-doc" type="button">Move to Trash</button>
                            {{-- </form> --}}
                        </li>
                    {{-- @endif --}}
                    @if ($type == 'received')
                    <li>
                        <button class="btn btn-primary vendor-upload" type="button">Upload RMA Details</button>
                    </li>
                    @endif
                @endrole
            </ul>
            <!-- Hidden inputs to track selected document and reason -->
            {{-- <input type="hidden" name="dispatch_id" value="{{ $dispatch_id ?? '' }}"> --}}

            {{-- <input type="hidden" name="dispatch_id" value="{{ $dispatch->id }}">
            <input type="hidden" name="doc-type" value="{{ $type }}"> --}}
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade {{($dtype ?? 'loan') == 'loan' ? 'show active':''}}" id="loan-pane" role="tabpanel" aria-labelledby="loan" tabindex="0">
                    @if(isset($loan_document) && $loan_document->count())
                        {{ $loan_document->links('pagination::bootstrap-5') }}
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @hasrole('master|super_admin|admin')
                                        {{-- @if ($type != 'rejected') --}}
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="loan_all" /></th>
                                        {{-- @endif --}}
                                    @elsehasanyrole('bo-maker|bo-checker')
                                        @if (in_array($type, ['pending', 'all', 'rejected']))
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="loan_all" /></th>
                                        @endif
                                    @elsehasrole('ro-supervisor')
                                        {{-- @if ($type === 'received') --}}
                                            <th srejectedcope="col" class="text-nowrap"><input type="checkbox" class="loan_all" /></th>
                                        {{-- @endif --}}
                                    @endhasrole
                                    {{-- @hasanyrole('master|bo-maker|bo-checker|ro-officer')
                                        @if (!in_array($type, ['received', 'rejected']))
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="loan_all" /> </th>
                                        @endif
                                    @endhasanyrole
                                    @role('ro-officer')
                                        @if ($type === 'received')
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="loan_all" /></th>
                                        @endif
                                    @endrole --}}
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                    <th scope="col" class="text-nowrap">Region</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    @endunless
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">CIF ID</th>
                                    <th scope="col" class="text-nowrap">Account Number</th>
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
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @if ($type == 'received')
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                    <th scope="col" class="text-nowrap">Actions</th>
                                    @endunless
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($loan_document)
                                    @foreach ($loan_document as $row)
                                        <tr>
                                            @hasrole('master|super_admin|admin')
                                                {{-- @if ($type != 'rejected') --}}
                                                    <td><input type="checkbox" class="loan @if(!in_array($row->status, [1,6])) d-none @endif" name="loan_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @elsehasanyrole('bo-maker|bo-checker')
                                                @if (in_array($type, ['pending', 'all', 'rejected']))
                                                    <td><input type="checkbox" class="loan @if(!in_array($row->status, [1,6])) d-none @endif" name="loan_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                @endif
                                            @elsehasrole('ro-supervisor')
                                                {{-- @if ($type === 'received') --}}
                                                {{-- <input type="checkbox" class="loan" data-id="{{ $row->id }}">     --}}
                                                <td><input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @endhasrole
                                            {{-- @hasanyrole('master|bo-maker|bo-checker')
                                            <td>
                                                @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                                    <input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}">
                                                @endif
                                            </td>
                                            @endhasanyrole
                                            @role('ro-officer')
                                            @if ($type === 'received' && $row->status == 1)
                                                <td><input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                            @endrole --}}
                                            <td><a href="{{ route('document.history',['id' => Crypt::encryptString($row->id),'type' => $type,'dtype' => 'loan'])}}">{{ $row->unique_ref_no }}</a></td>
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                            <td>{{ $row->region }}</td>
                                            <td>{{ $row->branch_name }}</td>
                                            @endunless
                                            <td>{{ $row->branch_code }}</td>
                                            <td>{{ $row->cif_id }}</td>
                                            <td>{{ $row->account_number }}</td>
                                            <td>{{ $row->loan_cycle }}</td>
                                            <td>{{ $row->customer_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td>{{ $row->channel }}</td>
                                            <td>{{ $row->loan_amount }}</td>
                                            <td>{{ $row->barcode }}</td>
                                            <td>{{ $row->glow_application_id }}</td>
                                            <td>{{ $row->loan_disbursement_type }}</td>
                                            <td>{{ $row->business_category }}</td>
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td> Received
                                                        @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                        @endif
                                                    </td>
                                                    @else
                                                        <td>{{ $row->statusName->name ?? '-' }}
                                                            @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                            @endif
                                                        </td>
                                                @endif
                                            @else
                                                <td>{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                    <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                        <img src="/images/info_icon.svg"/>
                                                    </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @if ($type == 'received')
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                            <td><button data-id="{{ $row->id }}" data-type="loan" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                            @endunless
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if(isset($loan_document) && $loan_document->count())
                        {{ $loan_document->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-muted">No documents found.</p>
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
                                    @hasrole('master|super_admin|admin')
                                        {{-- @if ($type !== 'rejected') --}}
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="goldloan_all" /></th>
                                        {{-- @endif --}}
                                    @elsehasanyrole('bo-maker|bo-checker')
                                        @if (in_array($type, ['pending', 'all', 'rejected']))
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="goldloan_all" /></th>
                                        @endif
                                    @elsehasrole('ro-supervisor')
                                        {{-- @if ($type === 'received') --}}
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="goldloan_all" /></th>
                                        {{-- @endif --}}
                                    @endhasrole
                                    {{-- @hasanyrole('master|bo-maker|bo-checker')
                                    @if (!in_array($type, ['received', 'rejected']))
                                    <th scope="col" class="text-nowrap"><input type="checkbox" class="goldloan_all"/> </th>
                                    @endif
                                    @endhasanyrole
                                    @role('ro-officer')
                                        @if ($type === 'received')
                                        <th scope="col" class="text-nowrap"><input type="checkbox" class="goldloan_all"/> </th>
                                        @endif
                                    @endrole --}}
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                    <th scope="col" class="text-nowrap">Region</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    @endunless
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">CIF ID</th>
                                    <th scope="col" class="text-nowrap">Account Number</th>
                                    <th scope="col" class="text-nowrap">Customer Name</th>
                                    <th scope="col" class="text-nowrap">Creation Date</th>
                                    <th scope="col" class="text-nowrap">Channel</th>
                                    <th scope="col" class="text-nowrap">Loan Amount</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @if ($type == 'received')
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                    <th scope="col" class="text-nowrap">Actions</th>
                                    @endunless
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($gold_loan_document)
                                    @foreach ($gold_loan_document as $row)
                                        <tr>
                                            @hasrole('master|super_admin|admin')
                                                {{-- @if ($type !== 'rejected') --}}
                                                    <td><input type="checkbox" class="goldloan @if(!in_array($row->status, [1,6])) d-none @endif" name="goldloan_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @elsehasanyrole('bo-maker|bo-checker')
                                                @if (in_array($type, ['pending', 'all', 'rejected']))
                                                    <td><input type="checkbox" class="goldloan @if(!in_array($row->status, [1,6])) d-none @endif" name="goldloan_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                @endif
                                            @elsehasrole('ro-supervisor')
                                                {{-- @if ($type === 'received') --}}
                                                    <td><input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @endhasrole
                                            {{-- @hasanyrole('master|bo-maker|bo-checker')
                                            <td>
                                                @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                                <input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ $row->id }}">
                                                @endif
                                            </td>
                                            @endhasanyrole
                                            @role('ro-officer')
                                            @if ($type === 'received' && $row->status == 1)
                                                <td><input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                            @endrole --}}
                                            <td><a href="{{ route('document.history',['id' => Crypt::encryptString($row->id),'type' => $type,'dtype' => 'goldloan'])}}">{{ $row->unique_ref_no }}</a></td>
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                            <td>{{ $row->region }}</td>
                                            <td>{{ $row->branch_name }}</td>
                                            @endunless
                                            <td>{{ $row->branch_code }}</td>
                                            <td>{{ $row->cif_id }}</td>
                                            <td>{{ $row->account_number }}</td>
                                            <td>{{ $row->customer_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td>{{ $row->channel }}</td>
                                            <td>{{ $row->loan_amount }}</td>
                                            <td>{{ $row->barcode }}</td>
                                            <td>{{ $row->business_category }}</td> 
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td> Received
                                                        @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                        @endif
                                                    </td>
                                                    @else
                                                        <td>{{ $row->statusName->name ?? '-' }}
                                                            @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                            @endif
                                                        </td>
                                                @endif
                                            @else
                                                <td>{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                    <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                        <img src="/images/info_icon.svg"/>
                                                    </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @if ($type == 'received')
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                            <td><button data-id="{{ $row->id }}" data-type="goldloan" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                            @endunless
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if(isset($gold_loan_document) && $gold_loan_document->count())
                        {{ $gold_loan_document->links('pagination::bootstrap-5') }}
                        @else
                        <p class="text-center text-muted">No documents found.</p> 
                    @endif
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'aof' ? 'show active':''}}" id="aof-pane" role="tabpanel" aria-labelledby="aof" tabindex="0">
                    @if(isset($account_opening_document) && $account_opening_document->count())
                        {{ $account_opening_document->links('pagination::bootstrap-5') }}
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @hasrole('master|super_admin|admin')
                                        {{-- @if ($type !== 'rejected') --}}
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="aof_all" /></th>
                                        {{-- @endif --}}
                                    @elsehasanyrole('bo-maker|bo-checker')
                                        @if (in_array($type, ['pending', 'all', 'rejected']))
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="aof_all" /></th>
                                        @endif
                                    @elsehasrole('ro-supervisor')
                                        {{-- @if ($type === 'received') --}}
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="aof_all" /></th>
                                        {{-- @endif --}}
                                    @endhasrole
                                    {{-- @hasanyrole('master|bo-maker|bo-checker')
                                    @if (!in_array($type, ['received', 'rejected']))
                                    <th scope="col" class="text-nowrap"><input type="checkbox" class="aof_all" /> </th>
                                    @endif
                                    @endhasanyrole
                                    @role('ro-officer')
                                        @if ($type === 'received')
                                        <th scope="col" class="text-nowrap"><input type="checkbox" class="aof_all" /> </th>
                                        @endif
                                    @endrole --}}
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                    <th scope="col" class="text-nowrap">Region</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    @endunless
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">CIF ID</th>
                                    <th scope="col" class="text-nowrap">Account Number</th>
                                    <th scope="col" class="text-nowrap">Customer Name</th>
                                    <th scope="col" class="text-nowrap">Creation Date</th>
                                    <th scope="col" class="text-nowrap">Channel</th>
                                    {{-- <th scope="col" class="text-nowrap">Barcode</th> --}}
                                    <th scope="col" class="text-nowrap">PGK No</th>
                                    <th scope="col" class="text-nowrap"> Scheme</th>
                                    <th scope="col" class="text-nowrap">Type</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @if ($type == 'received')
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                    <th scope="col" class="text-nowrap">Actions</th>
                                    @endunless
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($account_opening_document)
                                    @foreach ($account_opening_document as $row)
                                        <tr>
                                            @hasrole('master|super_admin|admin')
                                                {{-- @if ($type !== 'rejected') --}}
                                                    <td><input type="checkbox" class="aof @if(!in_array($row->status, [1,6])) d-none @endif" name="aof_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @elsehasanyrole('bo-maker|bo-checker')
                                                @if (in_array($type, ['pending', 'all', 'rejected']))
                                                    <td><input type="checkbox" class="aof @if(!in_array($row->status, [1,6])) d-none @endif" name="aof_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                @endif
                                            @elsehasrole('ro-supervisor')
                                                {{-- @if ($type === 'received') --}}
                                                    <td><input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @endhasrole
                                            {{-- @hasanyrole('master|bo-maker|bo-checker')
                                            <td>
                                                @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                                <input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ $row->id }}">
                                                @endif
                                            </td>
                                            @endhasanyrole
                                            @role('ro-officer')
                                            @if ($type === 'received' && $row->status == 1)
                                                <td><input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                            @endrole --}}
                                            <td><a href="{{ route('document.history',['id' => Crypt::encryptString($row->id),'type' => $type,'dtype' => 'aof'])}}">{{ $row->unique_ref_no }}</a></td>
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                            <td>{{ $row->region }}</td>
                                            <td>{{ $row->branch_name }}</td>
                                            @endunless
                                            <td>{{ $row->branch_code }}</td>
                                            <td>{{ $row->cif_id }}</td>
                                            <td>{{ $row->account_number }}</td>
                                            <td>{{ $row->customer_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td>{{ $row->channel }}</td>
                                            {{-- <td>{{ $row->barcode }}</td> --}}
                                            <td>{{ $row->pgk_no }}</td>
                                            <td>{{ $row->scheme ?? '-' }}</td>
                                            <td>{{ $row->type_of_account_opening }}</td>
                                            <td>{{ $row->business_category }}</td>
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td> Received
                                                        @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                        @endif
                                                    </td>
                                                    @else
                                                        <td>{{ $row->statusName->name ?? '-' }}
                                                            @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                            @endif
                                                        </td>
                                                @endif
                                            @else
                                                <td>{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                    <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                        <img src="/images/info_icon.svg"/>
                                                    </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @if ($type == 'received')
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                            <td><button data-id="{{ $row->id }}" data-type="aof" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                            @endunless
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @if(isset($account_opening_document) && $account_opening_document->count())
                        {{ $account_opening_document->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-muted">No documents found.</p>
                    @endif
                </div>
                <div class="tab-pane fade {{($dtype ?? '') == 'dtrf' ? 'show active':''}}" id="dtrf-pane" role="tabpanel" aria-labelledby="dtrf" tabindex="0">
                    @if(isset($dtrf_document) && $dtrf_document->count())
                        {{ $dtrf_document->links('pagination::bootstrap-5') }}
                    @endif
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @hasrole('master|super_admin|admin')
                                        {{-- @if ($type !== 'rejected') --}}
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="dtrf_all" /></th>
                                        {{-- @endif --}}
                                    @elsehasanyrole('bo-maker|bo-checker')
                                        @if (in_array($type, ['pending', 'all', 'rejected']))
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="dtrf_all" /></th>
                                        @endif
                                    @elsehasrole('ro-supervisor')
                                        {{-- @if ($type === 'received') --}}
                                            <th scope="col" class="text-nowrap"><input type="checkbox" class="dtrf_all" /></th>
                                        {{-- @endif --}}
                                    @endhasrole
                                    {{-- @hasanyrole('master|bo-maker|bo-checker')
                                    @if (!in_array($type, ['received', 'rejected']))
                                    <th scope="col" class="text-nowrap"><input type="checkbox" class="dtrf_all"/> </th>
                                    @endif
                                    @endhasanyrole
                                    @role('ro-officer')
                                        @if ($type === 'received')
                                        <th scope="col" class="text-nowrap"><input type="checkbox" class="dtrf_all"/> </th>
                                        @endif
                                    @endrole --}}
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                    <th scope="col" class="text-nowrap">Region</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    @endunless
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">DTR File Date</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Activity Date</th>
                                    @if ($type == 'received')
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                    <th scope="col" class="text-nowrap">Actions</th>
                                    @endunless
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($dtrf_document)
                                    @foreach ($dtrf_document as $row)
                                        <tr>
                                            @hasrole('master|super_admin|admin')
                                                {{-- @if ($type !== 'rejected') --}}
                                                    <td><input type="checkbox" class="dtrf @if(!in_array($row->status, [1,6])) d-none @endif" name="dtrf_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @elsehasanyrole('bo-maker|bo-checker')
                                                @if (in_array($type, ['pending', 'all', 'rejected']))
                                                    <td><input type="checkbox" class="dtrf @if(!in_array($row->status, [1,6])) d-none @endif" name="dtrf_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                @endif
                                            @elsehasrole('ro-supervisor')
                                                {{-- @if ($type === 'received') --}}
                                                    <td><input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ Crypt::encryptString($row->id) }}"></td>
                                                {{-- @endif --}}
                                            @endhasrole
                                            {{-- @hasanyrole('master|bo-maker|bo-checker')
                                            <td>
                                                @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                                <input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ $row->id }}">
                                                @endif
                                            </td>
                                            @endhasanyrole
                                            @role('ro-officer')
                                            @if ($type === 'received' && $row->status == 1)
                                                <td><input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                            @endrole --}}
                                            <td><a href="{{ route('document.history',['id' => Crypt::encryptString($row->id),'type' => $type,'dtype' => 'dtrf'])}}">{{ $row->unique_ref_no }}</a></td>
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                            <td>{{ $row->region }}</td>
                                            <td>{{ $row->branch_name }}</td>
                                            @endunless
                                            <td>{{ $row->branch_code }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->account_creation_date))}}</td>
                                            <td>{{ $row->barcode}}</td>
                                            <td>{{ $row->business_category}}</td>
                                            @hasrole('bo-maker|bo-checker|branch-user')
                                                @if ($row->status > 7)
                                                    <td> Received
                                                        @if (in_array($row->status, [6,7]))
                                                        <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                            <img src="/images/info_icon.svg"/>
                                                        </span>
                                                        @endif
                                                    </td>
                                                    @else
                                                        <td>{{ $row->statusName->name ?? '-' }}
                                                            @if (in_array($row->status, [6,7]))
                                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                                <img src="/images/info_icon.svg"/>
                                                            </span>
                                                            @endif
                                                        </td>
                                                @endif
                                            @else
                                                <td>{{ $row->statusName->name ?? '-' }}
                                                    @if (in_array($row->status, [6,7]))
                                                    <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason ?? 'No reason provided' }}">
                                                        <img src="/images/info_icon.svg"/>
                                                    </span>
                                                    @endif
                                                </td> 
                                            @endhasrole
                                            <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                            @if ($type == 'received')
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                            <td><button data-id="{{ $row->id }}" data-type="dtrf" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                            @endunless
                                            @endif
                                        </tr>
                                        @endforeach
                                    @endif
                            </tbody>
                        </table>
                    </div>
                    @if(isset($dtrf_document) && $dtrf_document->count())
                        {{ $dtrf_document->links('pagination::bootstrap-5') }}
                    @else
                        <p class="text-center text-muted">No documents found.</p>
                    @endif
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
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ro-officer']))
                <div class="col-12 mt-3">
                    <select class="form-select region" name="region">
                        <option value="">Select Region</option>
                        <option value="South" {{ ($filters['region'] ?? '') == 'South' ? 'selected' : '' }}>South</option>
                        <option value="North" {{ ($filters['region'] ?? '') == 'North' ? 'selected' : '' }}>North</option>
                        <option value="East" {{ ($filters['region'] ?? '') == 'East' ? 'selected' : '' }}>East</option>
                        <option value="West" {{ ($filters['region'] ?? '') == 'West' ? 'selected' : '' }}>West</option>
                    </select>
                </div>
                {{-- @endunless --}}
                <div class="col-12 mt-3">
                    <input type="number" class="form-control branch_code" placeholder="Branch Code" value="{{ old('branch_code', $filters['branch_code'] ?? '') }}" name="branch_code" min="0">
                </div>
                {{-- @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker'])) --}}
                <div class="col-12 mt-3">
                    <input type="text" class="form-control branch_name alphanumeric" placeholder="Branch Name" value="{{ old('branch_name', $filters['branch_name'] ?? '') }}" name="branch_name">
                </div>
                @endunless
                <div class="col-12 mt-3">
                    <input type="search" class="form-control cif_id alphanumeric" placeholder="CIF ID" value="{{ old('cif_id', $filters['cif_id'] ?? '') }}" name="cif_id">
                </div>
                <div class="col-12 mt-3">
                    <input type="search" class="form-control account_number alphanumeric" placeholder="Account Number" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
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
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Date From" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Date To" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control channel" placeholder="Channel" value="{{ old('channel', $filters['channel'] ?? '') }}" name="channel">
                </div>
                {{-- <div class="col-12 mt-3 d-none">
                    <select class="form-select" name="type">
                        <option value="">Loan Disbursement/Account Opening</option>
                        <option value="Esign" {{ ($filters['type'] ?? '') == 'Esign' ? 'selected' : '' }}>Esign</option>
                        <option value="Manual" {{ ($filters['type'] ?? '') == 'Manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div> --}}
                <div class="col-12 mt-3 d-none">
                    <input type="date" class="form-control" placeholder="DTR File Date" value="{{ old('dtr_file_date', $filters['dtr_file_date'] ?? '') }}" name="dtr_file_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control" placeholder="Business Category" value="{{ old('business_category', $filters['business_category'] ?? '') }}" name="business_category">
                </div>
                    @php
                        $statusLabels = [
                            1 => 'Pending',
                            2 => 'In Draft',
                            3 => 'Awaiting checker Approval',
                            4 => 'Dispatched',
                            5 => 'Received',
                            6 => 'Rejected',
                            7 => 'Received with query',
                            8 => 'IN',
                            9 => 'OUT',
                            10 => 'Permount',
                            11 => 'Destroyed',
                        ];

                        $selectedStatus = is_array($filters['status'] ?? '') ? null : ($filters['status'] ?? '');
                    @endphp
                <div class="col-12 mt-3">
                    <select class="form-select" name="status"
                        {{ isset($fixed_status) && !is_array($fixed_status) && $fixed_status != 5 ? 'disabled' : '' }}>
                        
                        @if(isset($fixed_status) && is_array($fixed_status) && $fixed_status === [5, 7])
                            <option value="5" {{ $selectedStatus == 5 ? 'selected' : '' }}>Received</option>
                            <option value="7" {{ $selectedStatus == 7 ? 'selected' : '' }}>Received with query</option>
                        @elseif(isset($fixed_status) && !is_array($fixed_status))
                            <option value="{{ $fixed_status }}" selected>
                                {{ $statusLabels[$fixed_status] ?? 'Status' }}
                            </option>
                        @else
                            <option value="">Select Status</option>
                            @foreach ($statusLabels as $key => $label)
                                @php
                                    $hideForRoles = [8, 9, 10, 11];
                                    $isRestricted = in_array($key, $hideForRoles) && auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']);
                                @endphp
                                @if (!$isRestricted)
                                    <option value="{{ $key }}" {{ $selectedStatus == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                    @if(isset($fixed_status) && !is_array($fixed_status) && $fixed_status != 5)
                        <input type="hidden" name="status" value="{{ $fixed_status }}">
                    @endif
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('accounts.index',['type' => $type,'dtype' => 'loan']) }}" class="btn btn-secondary">Clear</a> 
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="add-vendor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="rma-movement" action="{{ route('accounts.moved')}}" method="POST"> 
                @csrf
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Update Vendor Movement Information</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-2">
                        <input type="hidden" name="id">
                        <input type="hidden" name="dtype">
                        <input type="hidden" name="status" value="8">
                        <label for="lot_no" class="form-label">Lot No <span class="text-danger">*</span></label>
                        <input type="text" name="lot_no" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="category_of_document" class="form-label">Doc. Category <span class="text-danger">*</span></label>
                        {{-- <input type="text" name="category_of_document" class="form-control"> --}}
                        <select class="form-select document_type" name="category_of_document" required>
                            <option value="">Select Doc. Category</option>
                            <option value="CAT A1" {{ ($doc->category_of_document ?? '') == 'CAT A1' ? 'selected' : '' }}>CAT A1</option>
                            <option value="CAT A2" {{ ($doc->category_of_document ?? '') == 'CAT A2' ? 'selected' : '' }}>CAT A2</option>
                            <option value="CAT B"  {{ ($doc->category_of_document ?? '') == 'CAT B'  ? 'selected' : '' }}>CAT B</option>
                            <option value="CAT C"  {{ ($doc->category_of_document ?? '') == 'CAT C'  ? 'selected' : '' }}>CAT C</option>
                        </select>                                               
                    </div>
                    <div class="col-4 pb-2">
                        <label for="work_order_no" class="form-label">Work Order No <span class="text-danger">*</span></label>
                        <input type="text" name="work_order_no" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_name" class="form-label">Vendor Name <span class="text-danger">*</span></label>
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
                        <label for="vendor_movement_date" class="form-label">Date of Movement <span class="text-danger">*</span></label>
                        <input type="text" readonly name="vendor_movement_date" class="form-control flatpickr-date vendor_movement_date" value="{{ request('vendor_movement_date') }}" placeholder="Select date" autocomplete="off" readonly>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="file_barcode" class="form-label">File barcode <span class="text-danger">*</span></label>
                        <input type="text" name="file_barcode" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="box_barcode" class="form-label">Box Barcode <span class="text-danger">*</span></label>
                        <input type="text" name="box_barcode" class="form-control alphanumeric">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="date_added_to_vendor" class="form-label">Date of addition <span class="text-danger">*</span></label>
                        <input type="text" readonly name="date_added_to_vendor" class="form-control flatpickr-date date_added_to_vendor" value="{{ request('vendor_movement_date') }}"  placeholder="Select date" autocomplete="off" readonly>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
                 {{-- window.location.href = `{{ route('accounts.index',${type})}}`; --}}
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="upload-vendor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="rma-upload" action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header text-center">
                    <h5 class="mb-0 text-primary" id="modal-title">Upload Vendor Movement Information</h5>
                </div>
                <div class="modal-body">
                    <label for="excel_file" class="form-label">Upload File</label>  <a href="{{ route('vendor.sample.download') }}" class="btn btn-link"> Download Sample File </a>
                    <input type="file" name="excel_file" class="form-control file-validate"  data-ext="csv,xls,xlsx" required> 
                    <label class="text-danger mt-3" id="excel_file-error"></label>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
   
<script nonce='{{ env("CSP_NONCE") }}'>
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

        // let dtype = '{{$dtype}}';
        // $('button.nav-link').each(function() {
        //     let tab = $(this).attr('id');
        //     if(dtype == tab){    
        //         if(parseInt($(this).find('span').text()) > 0){
        //             $(this).addClass('active');
        //             $(`#${tab}-pane`).addClass('show active');
        //             return false;
        //         }
        //         else{
        //             $(this).removeClass('active');
        //             let tab = $(this).attr('id');
        //             $(`#${tab}-pane`).removeClass('show active');
        //         }
        //     }
        // });
        $(".loan_all").click(function () {
            $(".loan:visible").prop('checked', $(this).prop('checked'));
        });
        $(".goldloan_all").click(function () {
            $(".goldloan:visible").prop('checked', $(this).prop('checked'));
        });
        $(".aof_all").click(function () {
            $(".aof:visible").prop('checked', $(this).prop('checked'));
        });
        $(".dtrf_all").click(function () {
            $(".dtrf:visible").prop('checked', $(this).prop('checked'));
        });

        flatpickr(".flatpickr-date", {
            dateFormat: "d-m-Y",        
            maxDate: "today",         
            allowInput: false, 
            clickOpens: true
        });

        let selectedDocuments = [];
        
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
      
        $('.remove-doc').click(function (e) {
            e.preventDefault();
            const $selected = $('input[type=checkbox]:checked');
            if ($selected.length === 0) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please select at least one Document.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return;
            }
            let docIds = [];
            let type = '';
            let docMap = {};
            $selected.each(function () {
                let $el = $(this);
                let docId = $el.data('id');
                let className = ['loan', 'goldloan', 'dtrf', 'aof'].find(c => $el.hasClass(c));
                if (className) {
                    (docMap[className] ||= []).push(docId);
                }
            });
            const $badge = $(`#${type}-tab`).find('span.badge');
            let doc_count = parseInt($badge.text()) || 0;
            Swal.fire({
                // title: '<h5 class="mb-0 text-primary">Reason Required</h5>',
                title: 'Reason Required',
                input: "text",
                inputLabel: "Enter reason for deleting the document",
                inputPlaceholder: "Reason...",
                showCancelButton: true,
                confirmButtonText: 'Confirm Delete',
                cancelButtonText: "Cancel",
                customClass: {
                    popup: 'rounded-3 shadow',
                    title: 'mb-0 text-primary',
                    input: 'alphanumeric',
                    confirmButton: 'btn btn-primary btn-lg',
                    cancelButton: 'btn btn-secondary btn-lg',
                },
                inputValidator: value => !value && "Reason is required!"
            }).then(result => {
                if (result.isConfirmed) {
                    $.post(`{{ route('document.remove') }}`, {
                        _token: $('input[name="_token"]').val(),
                        doc_ids: docMap,
                        type: type,
                        reason: result.value,
                    })
                    .done(() => {
                        Swal.fire({title: "Deleted!", text: "Document removed successfully.", icon: "success"}).then(() => {
                            $selected.closest('tr').remove();
                            // $badge.text(doc_count - $selected.length);
                            Object.entries(docMap).forEach(([type, ids]) => {
                                const $badge = $(`#${type}`).find('span.badge');
                                const current = parseInt($badge.text()) || 0;
                                const newCount = Math.max(current - ids.length, 0);
                                $badge.text(newCount);
                            });
                        });
                    })
                    .fail(() => {
                        Swal.fire({title: "Error!", text: "Something went wrong!", icon: "error"});
                    });
                }
            });
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
                e.preventDefault(); /^\d+$/
                Swal.fire({
                    title: "Warning!",
                    text: "Please select at least one filter option.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            }
        });

        $('.vendor-upload').click(function () {
            $('#upload-vendor').modal('show');
            // $('#add-vendor').modal('show');
        });

        $('.add-vendor').click(function () {
            $('input[name="id"]').val($(this).data('id'));
            $('input[name="dtype"]').val($(this).data('type'));
            $('#add-vendor').modal('show');
        });

        $('#rma-movement').validate({
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
                }
            // },
            // submitHandler: function (form) {

            //     let documentTypes = ['loan', 'goldloan', 'aof', 'dtrf'];
            //     let hasSelection = false;

            //     $('#rma-movement').find('input[name$="_ids[]"]').remove();

            //     documentTypes.forEach(function (type) {
            //         let ids = [];

            //         $('input.' + type + ':checked').each(function () {
            //             ids.push($(this).data('id'));
            //         });

            //         if (ids.length > 0) {
            //             hasSelection = true;

            //             ids.forEach(function (id) {
            //                 $('#rma-movement').append(
            //                     '<input type="hidden" name="' + type + '_ids[]" value="' + id + '">'
            //                 );
            //             });
            //         }
            //     });

            //     $('#rma-movement').submit();
            }
        });
    });
</script>


@endsection