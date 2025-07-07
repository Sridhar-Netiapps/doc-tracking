@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="d-flex page-heading">
                <h3 >{{ ucfirst($type) }} Docs</h3>
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
                        MB Loan Docs <span class="badge text-bg-warning">{{ $loan_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'gold_loan' ? 'active':''}}" id="goldloan-tab" data-bs-toggle="tab" data-bs-target="#goldloan-tab-pane" type="button" role="tab" aria-controls="goldloan-tab-pane" aria-selected="false">
                        Gold Loan Docs <span class="badge text-bg-warning">{{ $gold_loan_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'aof' ? 'active':''}}" id="aof-tab" data-bs-toggle="tab" data-bs-target="#aof-tab-pane" type="button" role="tab" aria-controls="aof-tab-pane" aria-selected="false">
                        Liablities Docs <span class="badge text-bg-warning">{{ $aof_total }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'dtrf' ? 'active':''}}" id="dtrf-tab" data-bs-toggle="tab" data-bs-target="#dtrf-tab-pane" type="button" role="tab" aria-controls="dtrf-tab-pane" aria-selected="false">
                        DTR Files <span class="badge text-bg-warning">{{ $dtrf_total }}</span>
                    </button>
                </li>
                @hasanyrole('master|bo-maker|bo-checker')
                    @if (!in_array($type, ['received', 'rejected']))
                        <li class="ms-auto">
                            <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed">
                                @csrf
                                <button class="btn btn-primary proceed" type="button">Proceed</button>
                            </form>
                        </li>
                    @endif
                @endhasanyrole

                @role('ro-user|super_admin|master')
                    {{-- @if ($type != 'rejected' && $type != 'pending') --}}
                        <li class="ms-auto">
                            {{-- <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed"> --}}
                                {{-- @csrf --}}
                                <button class="btn btn-danger btn-sm remove-doc" type="button">Delete</button>
                            {{-- </form> --}}
                        </li>
                    {{-- @endif --}}
                    @if ($type === 'received')
                    <li style="margin-left: 10px;">
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
                <div class="tab-pane fade {{($filters['document_type'] ?? 'loan') == 'loan' ? 'show active':''}}" id="loanac-tab-pane" role="tabpanel" aria-labelledby="loanac-tab" tabindex="0">
                    @if(isset($loan_document) && $loan_document->count())
                        {{ $loan_document->links('pagination::bootstrap-5') }}
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasrole('master')
                                    @if ($type !== 'rejected')
                                        <th scope="col"><input type="checkbox" class="loan_all" /></th>
                                    @endif
                                @elsehasanyrole('bo-maker|bo-checker')
                                    @if (in_array($type, ['pending', 'all']))
                                        <th scope="col"><input type="checkbox" class="loan_all" /></th>
                                    @endif
                                @elsehasrole('ro-user')
                                    {{-- @if ($type === 'received') --}}
                                        <th scope="col"><input type="checkbox" class="loan_all" /></th>
                                    {{-- @endif --}}
                                @endhasrole
                                {{-- @hasanyrole('master|bo-maker|bo-checker|ro-user')
                                    @if (!in_array($type, ['received', 'rejected']))
                                        <th scope="col"><input type="checkbox" class="loan_all" /> </th>
                                    @endif
                                @endhasanyrole
                                @role('ro-user')
                                    @if ($type === 'received')
                                        <th scope="col"><input type="checkbox" class="loan_all" /></th>
                                    @endif
                                @endrole --}}
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
                                <th scope="col">Type of Loan<br>Disbursement</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Activity Date</th>
                                @if ($type == 'received')
                                @hasrole('ro-user')
                                <th scope="col">Actions</th>
                                @endhasrole
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($loan_document)
                                @foreach ($loan_document as $row)
                                    <tr>
                                        @hasrole('master')
                                            @if ($type !== 'rejected')
                                                <td><input type="checkbox" class="loan @if(!in_array($row->status, [1,6])) d-none @endif" name="loan_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasanyrole('bo-maker|bo-checker')
                                            @if (in_array($type, ['pending', 'all']))
                                                <td><input type="checkbox" class="loan @if(!in_array($row->status, [1,6])) d-none @endif" name="loan_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasrole('ro-user')
                                            {{-- @if ($type === 'received') --}}
                                            {{-- <input type="checkbox" class="loan" data-id="{{ $row->id }}">     --}}
                                            <td><input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}"></td>
                                            {{-- @endif --}}
                                        @endhasrole
                                        {{-- @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                                <input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        @role('ro-user')
                                        @if ($type === 'received' && $row->status == 1)
                                            <td><input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}"></td>
                                        @endif
                                        @endrole --}}
                                        <td><a href="{{ route('document.history',['id' => $row->id, 'type' => 'loan'])}}">{{ $row->unique_ref_no }}</a></td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
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
                                        <td>{{ $row->loan_disbursement_type }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}
                                            @if (in_array($row->status, [6,7]))
                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                <img src="/images/info_icon.svg"/>
                                              </span>
                                            @endif
                                        </td> 
                                        <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                        @if ($type == 'received')
                                        @hasrole('ro-user')
                                        <td><button data-id="{{ $row->id }}" data-type="loan" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                       @endhasrole
                                        @endif
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
                    @if(isset($gold_loan_document) && $gold_loan_document->count())
                        {{ $gold_loan_document->links('pagination::bootstrap-5') }}
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasrole('master')
                                    @if ($type !== 'rejected')
                                        <th scope="col"><input type="checkbox" class="goldloan_all" /></th>
                                    @endif
                                @elsehasanyrole('bo-maker|bo-checker')
                                    @if (in_array($type, ['pending', 'all']))
                                        <th scope="col"><input type="checkbox" class="goldloan_all" /></th>
                                    @endif
                                @elsehasrole('ro-user')
                                    {{-- @if ($type === 'received') --}}
                                        <th scope="col"><input type="checkbox" class="goldloan_all" /></th>
                                    {{-- @endif --}}
                                @endhasrole
                                {{-- @hasanyrole('master|bo-maker|bo-checker')
                                @if (!in_array($type, ['received', 'rejected']))
                                <th scope="col"><input type="checkbox" class="goldloan_all"/> </th>
                                @endif
                                @endhasanyrole
                                @role('ro-user')
                                    @if ($type === 'received')
                                    <th scope="col"><input type="checkbox" class="goldloan_all"/> </th>
                                    @endif
                                @endrole --}}
                                <th scope="col">Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col">Region</th>
                                <th scope="col">Branch Name</th>
                                @endunless
                                <th scope="col">Branch Code</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">Account Number</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Account Creation Date</th>
                                <th scope="col">Channel</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Activity Date</th>
                                @if ($type == 'received')
                                @hasrole('ro-user')
                                <th scope="col">Actions</th>
                                @endhasrole
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($gold_loan_document)
                                @foreach ($gold_loan_document as $row)
                                    <tr>
                                        @hasrole('master')
                                            @if ($type !== 'rejected')
                                                <td><input type="checkbox" class="goldloan @if(!in_array($row->status, [1,6])) d-none @endif" name="goldloan_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasanyrole('bo-maker|bo-checker')
                                            @if (in_array($type, ['pending', 'all']))
                                                <td><input type="checkbox" class="goldloan @if(!in_array($row->status, [1,6])) d-none @endif" name="goldloan_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasrole('ro-user')
                                            {{-- @if ($type === 'received') --}}
                                                <td><input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ $row->id }}"></td>
                                            {{-- @endif --}}
                                        @endhasrole
                                        {{-- @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                            <input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        @role('ro-user')
                                        @if ($type === 'received' && $row->status == 1)
                                            <td><input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ $row->id }}"></td>
                                        @endif
                                        @endrole --}}
                                        <td><a href="{{ route('document.history',['id' => $row->id, 'type' => 'goldloan'])}}">{{ $row->unique_ref_no }}</a></td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        @endunless
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td>{{ $row->business_category }}</td> 
                                        <td>{{ $row->statusName->name ?? '-' }}
                                            @if (in_array($row->status, [6,7]))
                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                <img src="/images/info_icon.svg"/>
                                              </span>
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                        @if ($type == 'received')
                                        @hasrole('ro-user')
                                        <td><button data-id="{{ $row->id }}" data-type="goldloan" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                        @endhasrole
                                        @endif
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
                    @if(isset($account_opening_document) && $account_opening_document->count())
                        {{ $account_opening_document->links('pagination::bootstrap-5') }}
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasrole('master')
                                    @if ($type !== 'rejected')
                                        <th scope="col"><input type="checkbox" class="aof_all" /></th>
                                    @endif
                                @elsehasanyrole('bo-maker|bo-checker')
                                    @if (in_array($type, ['pending', 'all']))
                                        <th scope="col"><input type="checkbox" class="aof_all" /></th>
                                    @endif
                                @elsehasrole('ro-user')
                                    {{-- @if ($type === 'received') --}}
                                        <th scope="col"><input type="checkbox" class="aof_all" /></th>
                                    {{-- @endif --}}
                                @endhasrole
                                {{-- @hasanyrole('master|bo-maker|bo-checker')
                                @if (!in_array($type, ['received', 'rejected']))
                                <th scope="col"><input type="checkbox" class="aof_all" /> </th>
                                @endif
                                @endhasanyrole
                                @role('ro-user')
                                    @if ($type === 'received')
                                    <th scope="col"><input type="checkbox" class="aof_all" /> </th>
                                    @endif
                                @endrole --}}
                                <th scope="col">Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col">Region</th>
                                <th scope="col">Branch Name</th>
                                @endunless
                                <th scope="col">Branch Code</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">Account Number</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Account Creation Date</th>
                                <th scope="col">Channel</th>
                                <th scope="col">Type of Account Opening</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Activity Date</th>
                                @if ($type == 'received')
                                @hasrole('ro-user')
                                <th scope="col">Actions</th>
                                @endhasrole
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($account_opening_document)
                                @foreach ($account_opening_document as $row)
                                    <tr>
                                        @hasrole('master')
                                            @if ($type !== 'rejected')
                                                <td><input type="checkbox" class="aof @if(!in_array($row->status, [1,6])) d-none @endif" name="aof_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasanyrole('bo-maker|bo-checker')
                                            @if (in_array($type, ['pending', 'all']))
                                                <td><input type="checkbox" class="aof @if(!in_array($row->status, [1,6])) d-none @endif" name="aof_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasrole('ro-user')
                                            {{-- @if ($type === 'received') --}}
                                                <td><input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ $row->id }}"></td>
                                            {{-- @endif --}}
                                        @endhasrole
                                        {{-- @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                            <input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        @role('ro-user')
                                        @if ($type === 'received' && $row->status == 1)
                                            <td><input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ $row->id }}"></td>
                                        @endif
                                        @endrole --}}
                                        <td><a href="{{ route('document.history',['id' => $row->id, 'type' => 'aof'])}}">{{ $row->unique_ref_no }}</a></td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        @endunless
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td>{{ $row->type_of_account_opening }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}
                                            @if (in_array($row->status, [6,7]))
                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                <img src="/images/info_icon.svg"/>
                                              </span>
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                        @if ($type == 'received')
                                        @hasrole('ro-user')
                                        <td><button data-id="{{ $row->id }}" data-type="aof" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                        @endhasrole
                                        @endif
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
                    @if(isset($dtrf_document) && $dtrf_document->count())
                        {{ $dtrf_document->links('pagination::bootstrap-5') }}
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                @hasrole('master')
                                    @if ($type !== 'rejected')
                                        <th scope="col"><input type="checkbox" class="dtrf_all" /></th>
                                    @endif
                                @elsehasanyrole('bo-maker|bo-checker')
                                    @if (in_array($type, ['pending', 'all']))
                                        <th scope="col"><input type="checkbox" class="dtrf_all" /></th>
                                    @endif
                                @elsehasrole('ro-user')
                                    {{-- @if ($type === 'received') --}}
                                        <th scope="col"><input type="checkbox" class="dtrf_all" /></th>
                                    {{-- @endif --}}
                                @endhasrole
                                {{-- @hasanyrole('master|bo-maker|bo-checker')
                                @if (!in_array($type, ['received', 'rejected']))
                                <th scope="col"><input type="checkbox" class="dtrf_all"/> </th>
                                @endif
                                @endhasanyrole
                                @role('ro-user')
                                    @if ($type === 'received')
                                    <th scope="col"><input type="checkbox" class="dtrf_all"/> </th>
                                    @endif
                                @endrole --}}
                                <th scope="col">Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col">Region</th>
                                <th scope="col">Branch Name</th>
                                @endunless
                                <th scope="col">Branch Code</th>
                                <th scope="col">DTR File Date</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Activity Date</th>
                                @if ($type == 'received')
                                @hasrole('ro-user')
                                <th scope="col">Actions</th>
                                @endhasrole
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dtrf_document)
                                @foreach ($dtrf_document as $row)
                                    <tr>
                                        @hasrole('master')
                                            @if ($type !== 'rejected')
                                                <td><input type="checkbox" class="dtrf @if(!in_array($row->status, [1,6])) d-none @endif" name="dtrf_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasanyrole('bo-maker|bo-checker')
                                            @if (in_array($type, ['pending', 'all']))
                                                <td><input type="checkbox" class="dtrf @if(!in_array($row->status, [1,6])) d-none @endif" name="dtrf_ids[]" data-id="{{ $row->id }}"></td>
                                            @endif
                                        @elsehasrole('ro-user')
                                            {{-- @if ($type === 'received') --}}
                                                <td><input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ $row->id }}"></td>
                                            {{-- @endif --}}
                                        @endhasrole
                                        {{-- @hasanyrole('master|bo-maker|bo-checker')
                                        <td>
                                            @if ($row->status == 1 && !in_array($type, ['received', 'rejected']))
                                            <input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ $row->id }}">
                                            @endif
                                        </td>
                                        @endhasanyrole
                                        @role('ro-user')
                                        @if ($type === 'received' && $row->status == 1)
                                            <td><input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ $row->id }}"></td>
                                        @endif
                                        @endrole --}}
                                        <td><a href="{{ route('document.history',['id' => $row->id, 'type' => 'dtrf'])}}">{{ $row->unique_ref_no }}</a></td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        @endunless
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date))}}</td>
                                        <td>{{ $row->business_category}}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}
                                            @if (in_array($row->status, [6,7]))
                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                <img src="/images/info_icon.svg"/>
                                              </span>
                                            @endif
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                        @if ($type == 'received')
                                        @hasrole('ro-user')
                                        <td><button data-id="{{ $row->id }}" data-type="dtrf" class="btn btn-primary btn-sm add-vendor" type="button">Update</button></td>
                                        @endhasrole
                                        @endif
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
                <div class="col-12 mt-3">
                    <input type="search" class="form-control cif_id" placeholder="CIF ID" value="{{ old('cif_id', $filters['cif_id'] ?? '') }}" name="cif_id">
                </div>
                <div class="col-12 mt-3">
                    <input type="search" class="form-control account_number" placeholder="Account Number" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
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
                    <select class="form-select" name="status">
                        <option value="">Select Status</option>
                        @foreach ($process_statuses as $status)
                            <option value="{{ $status->id }}" {{ ($filters['status'] ?? '') == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>                                      
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('accounts.index',$type) }}" class="btn btn-secondary">Clear</a> 
                    {{-- {{dd($type)}} --}}
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
                        <input type="hidden" name="type">
                        <label for="lot_no" class="form-label">Lot No.</label>
                        <input type="number" name="lot_no" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="category_of_document" class="form-label">Category of the Document.</label>
                        <input type="text" name="category_of_document" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="work_order_no" class="form-label">Work Order No.</label>
                        <input type="number" name="work_order_no" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_name" class="form-label">Vendor Name</label>
                        <input type="text" name="vendor_name" class="form-control">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="vendor_movement_date" class="form-label">Date of Vendor Movement.</label>
                        <input type="text" readonly name="vendor_movement_date" class="form-control datepicker vendor_movement_date" value="{{ request('vendor_movement_date') }}">
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
                        <input type="text" readonly name="date_added_to_vendor" class="form-control datepicker date_added_to_vendor" value="{{ request('vendor_movement_date') }}">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
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
                    <label for="excel_file" class="form-label">Upload File</label>
                    <input type="file" name="excel_file" class="form-control" required>
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
      
//         function toggleDeleteButton() {
//     const hasChecked = $('input[type=checkbox].loan:checked, input[type=checkbox].goldloan:checked, input[type=checkbox].dtrf:checked, input[type=checkbox].aof:checked').length > 0;
//     $('.remove-doc').prop('disabled', !hasChecked);
// }

// $(document).on('change', 'input[type=checkbox]', toggleDeleteButton);

$('.remove-doc').click(function (e) {
    e.preventDefault();
    
    let selected = $('input[type=checkbox]:checked');
    if (selected.length === 0) {
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

    selected.each(function () {
        docIds.push($(this).data('id'));
        if (!type) {
            if ($(this).hasClass('loan')) type = 'loan';
            if ($(this).hasClass('goldloan')) type = 'goldloan';
            if ($(this).hasClass('dtrf')) type = 'dtrf';
            if ($(this).hasClass('aof')) type = 'aof';
        }
    });

    Swal.fire({
        title: '<h5 class="mb-0 text-primary">Reason Required</h5>',
        input: "text",
        inputLabel: "Enter reason for deleting the document:",
        inputPlaceholder: "Reason...",
        showCancelButton: true,
        confirmButtonText: '<b>Confirm Delete</b>',
        cancelButtonText: "Cancel",
        customClass: {
            popup: 'rounded-3 shadow',
            confirmButton: 'btn btn-primary btn-lg',
            cancelButton: 'btn btn-secondary btn-lg',
        },
        inputValidator: (value) => {
            if (!value) return "Reason is required!";
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(`{{ route('document.remove') }}`, {
                _token: $('input[name="_token"]').val(),
                doc_ids: docIds,
                type: type,
                reason: result.value,
            })
            .done(function () {
                Swal.fire("Deleted!", "Document removed successfully.", "success").then(() => {
                    // ✅ Remove deleted rows without reloading
                    // selected.closest('tr').remove();
                    location.reload();
                    // 👇 Check if any checkboxes are left selected
                    // let anyChecked = $('input[type=checkbox]:checked').length > 0;
                    // $('.remove-doc').prop('disabled', !anyChecked);
                });
            })
            .fail(function () {
                Swal.fire("Error!", "Something went wrong!", "error");
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
                e.preventDefault(); 
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
            $('input[name="type"]').val($(this).data('type'));
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
            },
            submitHandler: function (form) {

                let documentTypes = ['loan', 'goldloan', 'aof', 'dtrf'];
                let hasSelection = false;

                $('#rma-movement').find('input[name$="_ids[]"]').remove();

                documentTypes.forEach(function (type) {
                    let ids = [];

                    $('input.' + type + ':checked').each(function () {
                        ids.push($(this).data('id'));
                    });

                    if (ids.length > 0) {
                        hasSelection = true;

                        ids.forEach(function (id) {
                            $('#rma-movement').append(
                                '<input type="hidden" name="' + type + '_ids[]" value="' + id + '">'
                            );
                        });
                    }
                });

                $('#rma-movement').submit();
            }
        });
    });
</script>


@endsection