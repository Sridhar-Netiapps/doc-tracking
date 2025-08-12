@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="d-flex justify-content-between align-items-center page-heading">
                <h3>Document Journey</h3>
                <a href="{{ route('accounts.index',['type' => $type,'dtype' => $dtype]) }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
        <div class="col-1"></div>
    </div>  
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="row g-3">
                <div class="col-9">
                    <div class="filter-bg h-100">
                        <div class="tab-content bg-white" id="myTabContent">
                            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">S No</th>
                                            <th scope="col">Previous Status</th>
                                            <th scope="col">Current Status</th>
                                            <th scope="col">Done By</th>
                                            <th scope="col">Done On</th>                                
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($history as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $row->oldStatus->name ?? '-' }}</td>
                                            <td>{{ $row->newStatus->name ?? '-' }}
                                                @if (in_array($row->current_status, [6,7]) && $row->remarks != null)
                                                <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->remarks }}">
                                                    <img src="/images/info_icon.svg"/>
                                                </span>
                                                @endif
                                            </td>
                                            <td>{{ $row->creator->first_name ?? 'System' }} {{ $row->creator->last_name ?? 'Generated' }} </td>
                                            <td>{{ date('d-m-Y h:i A', strtotime($row->created_at)) ?? '-' }}</td>
                                        </tr>                                
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="filter-bg h-100">
                        <div class="p-2 border-bottom">
                            <label>Document Type</label>
                            <h6><b>
                                @if ($dtype == 'loan')
                                    MB Loan
                                @elseif ($dtype == 'goldloan')
                                    Gold Loan
                                @elseif ($dtype == 'aof')
                                    Liablities
                                @elseif ($dtype == 'dtrf')
                                    DTR File
                                @endif
                            </b></h6>
                        </div>
                        @if(!empty($document->unique_ref_no))
                        <div class="p-2 border-bottom">
                            <label>Unique Number</label>
                            <h6><b>{{ $document->unique_ref_no }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->branch_code))
                        <div class="p-2 border-bottom">
                            <label>Branch Code</label>
                            <h6><b>{{ $document->branch_code }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->branch_name))
                        <div class="p-2 border-bottom">
                            <label>Branch Name</label>
                            <h6><b>{{ $document->branch_name }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->cif_id))
                        <div class="p-2 border-bottom">
                            <label>CIF ID</label>
                            <h6><b>{{ $document->cif_id }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->account_number))
                        <div class="p-2 border-bottom">
                            <label>Account Number</label>
                            <h6><b>{{ $document->account_number }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->customer_name))
                        <div class="p-2 border-bottom">
                            <label>Customer Name</label>
                            <h6><b>{{ $document->customer_name }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->account_creation_date))
                        <div class="p-2 border-bottom">
                            <label>Account Creation Date</label>
                            <h6><b>{{ $document->account_creation_date }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->loan_cycle))
                        <div class="p-2 border-bottom">
                            <label>Scheme</label>
                            <h6><b>{{ $document->loan_cycle }}</b></h6>
                        </div>
                        @endif

                        @if(!empty($document->scheme))
                        <div class="p-2 border-bottom">
                            <label>Scheme</label>
                            <h6><b>{{ $document->scheme }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->channel))
                        <div class="p-2 border-bottom">
                            <label>Channel</label>
                            <h6><b>{{ $document->channel }}</b></h6>
                        </div>
                        @endif

                        @if(!empty($document->business_category))
                        <div class="p-2 border-bottom">
                            <label>Business Category</label>
                            <h6><b>{{ $document->business_category }}</b></h6>
                        </div>
                        @endif
                    
                        @if(!empty($document->type_of_account_opening))
                        <div class="p-2 border-bottom">
                            <label>Loan Disbursement Type / Account Opening Type</label>
                            <h6><b>{{ $document->type_of_account_opening }}</b></h6>
                        </div>
                        @endif
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection
