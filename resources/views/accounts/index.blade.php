@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h3>{{ ucfirst($type) }} Accounts</h3>
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
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Selected Accounts <span class="badge text-bg-warning">{{$allDocuments != Null ?count($allDocuments):0}}</span></button>
                </li>
                <li class="ms-auto">
                    <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed">
                        @csrf
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-courier" type="button">Add Courier Details</button>
                        <a class="btn btn-secondary" href="{{ route('accounts.index',$type)}}">Go Back</a>
                    </form>
                </li>
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <table class="table table-hover">
                        <thead>
                        <table class="table table-bordered table-striped">
                        <tr> 
                            <th scope="col"><input type="checkbox"/> </th>     
                             <th scope="col"> Document Type</th>
                             <th scope="col"> Unique Number</th>
                             <th scope="col"> Region</th>
                             <th scope="col"> Branch Code</th>
                             <th scope="col"> Branch Name</th>
                             <th scope="col"> CIF ID</th>
                             <th scope="col"> Account Number</th>
                             <th scope="col"> Loan Cycle</th>
                             <th scope="col"> Scheme</th>
                             <th scope="col"> Customer Name</th>
                             <th scope="col"> Account Creation Date</th>
                             <th scope="col"> Channel</th>
                             <th scope="col"> Loan Disbursement Type / Account Opening</th>
                             <th scope="col"> DTR File Date</th>
                             <th scope="col"> Business Category</th>
                             <th scope="col"> Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allDocuments as $doc)
                            <tr>
                                <th scope="col"><input type="checkbox"/> </th>     
                                <td>{{ ucfirst($doc->doc_type) }}</td>
                                <td>{{ $doc->unique_ref_no ?? '-' }}</td>
                                <td>{{ $doc->region ?? '-' }}</td>
                                <td>{{ $doc->branch_code ?? '-' }}</td>
                                <td>{{ $doc->branch_name ?? '-' }}</td>
                                <td>{{ $doc->cif_id ?? '-' }}</td>
                                <td>{{ $doc->account_number ?? '-' }}</td>
                                <td>{{ $doc->loan_cycle ?? '-' }}</td>
                                <td>{{ $doc->scheme ?? '-' }}</td>
                                <td>{{ $doc->customer_name ?? '-' }}</td>
                                <td>{{ $doc->account_creation_date ?? '-' }}</td>
                                <td>{{ $doc->channel ?? '-' }}</td>
                                <td>{{ $doc->loan_disbursement_type ?? $doc->type_of_account_opening ?? '-' }}</td>
                                <td>{{ $doc->dtr_file_date ?? '-' }}</td>
                                <td>{{ $doc->business_category ?? '-' }}</td>
                                <td>{{ $doc->status ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection