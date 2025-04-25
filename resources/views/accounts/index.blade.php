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
                        <tr>
                            <th scope="col"><input type="checkbox" /> </th>
                            <th scope="col">Unique Number</th>
                            <th scope="col">CIF ID</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Account Creation Date</th>
                            <th scope="col">Channel</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Account Creation Date</th>
                            <th scope="col">Channel</th>
                            <th scope="col">Loan Cycle</th>
                            <th scope="col">PGK No. / Glow application ID</th>
                            <th scope="col" class="border-start">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if($allDocuments)
                                @foreach ($allDocuments as $row)
                                    <tr>
                                        <td><input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}"></td>
                                        <td>{{ $row->unique_ref_no }}</td>
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->loan_cycle }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ $row->account_creation_date }}</td>
                                        <td>{{ $row->channel }}</td>
                                        {{-- <td>{{ $row->barcode }}</td> --}}
                                        <td>{{ $row->loan_disbursement_type }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        {{-- <td>{{ ucwords(str_replace("_"," ",$row->business_type)) }}</td>
                                        <td>{{ ucwords(str_replace("-"," ",$row->rbi_classification)) }}</td> --}}
                                        {{-- <td>{{ $row->branch_office_type }}</td>
                                        <td>{{ $row->pincode }}</td>
                                        <td>{{ $row->city }}</td> --}}
                                        <td>{{ $row->status }}</td>
                                        </td>
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
@endsection