@extends('layouts.app')
@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
        </div>
        <div class="col-1"></div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h3>{{ ucfirst($type) }} Documents</h3>
        </div>
        <div class="col-1"></div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
            <div class="col-10">
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
                <li class="ms-auto">
                    <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed">
                        @csrf
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-courier" type="button">Add Courier Details</button>
                        <a class="btn btn-secondary" href="{{ route('accounts.index',['type' => $type,'dtype' => 'loan'])}}">Go Back</a>
                    </form>
                </li>
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="loanac-tab-pane" role="tabpanel" aria-labelledby="loanac-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="loan_all" /> </th>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Region</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
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
                                        <td><input type="checkbox" class="loan" name="loan_ids[]" data-id="{{ $row->id }}"></td>
                                        <td>{{ $row->unique_ref_no }}</td>
                                        <td>{{ $row->region }}</td>
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->loan_cycle }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
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
                </div>
                <div class="tab-pane fade" id="goldloan-tab-pane" role="tabpanel" aria-labelledby="goldloan-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="goldloan_all"/> </th>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Region</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
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
                                        <td><input type="checkbox" class="goldloan" name="goldloan_ids[]" data-id="{{ $row->id }}"></td>
                                        
                                    <td>{{ $row->unique_ref_no }}</td> 
                                    <td>{{ $row->region }}</td> 
                                    <td>{{ $row->branch_code }}</td>
                                    <td>{{ $row->branch_name }}</td>
                                    <td>{{ $row->cif_id }}</td>
                                    <td>{{ $row->account_number }}</td>
                                    <td>{{ $row->customer_name }}</td>
                                    <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                    <td>{{ $row->channel }}</td>
                                    <td>{{ $row->business_category }}</td> 
                                    <td>
                                        {{ $row->statusName->name ?? '-' }}
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
                                <th scope="col"><input type="checkbox" class="aof_all" /> </th>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Region</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
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
                                        <td><input type="checkbox" class="aof" name="aof_ids[]" data-id="{{ $row->id }}"></td>
                                        
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    {{-- <td><input type="checkbox" /></td> --}}
                                    <td>{{ $row->unique_ref_no }}</td>
                                    <td>{{ $row->region }}</td>
                                    <td>{{ $row->branch_code }}</td>
                                    <td>{{ $row->branch_name }}</td>
                                    <td>{{ $row->cif_id }}</td>
                                    <td>{{ $row->account_number }}</td>
                                    {{-- <td>{{ $row->loan_cycle }}</td> --}}
                                    <td>{{ $row->customer_name }}</td>
                                    <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
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
                </div>
                <div class="tab-pane fade" id="dtrf-tab-pane" role="tabpanel" aria-labelledby="dtrf-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="dtrf_all"/> </th>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Region</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
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
                               
                            </tr>
                        </thead>
                        <tbody>
                            @if ($dtrf_document)
                                @foreach ($dtrf_document as $row)
                                    <tr>
                                        <td><input type="checkbox" class="dtrf" name="dtrf_ids[]" data-id="{{ $row->id }}"></td>
                                        
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    {{-- <td><input type="checkbox" /></td> --}}
                                    <td>{{ $row->unique_ref_no }}</td>
                                    <td>{{ $row->region }}</td>
                                    <td>{{ $row->branch_code }}</td>
                                    <td>{{ $row->branch_name }}</td>
                                    <td>{{ date('d-m-Y', strtotime($row->account_creation_date))}}</td>
                                    {{-- <td>{{ $row->barcode }}</td> --}}
                                    <td>{{ $row->business_category}}</td>
                                    {{-- <td>{{ $row->customer_name }}</td>
                                    <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
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
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
<div class="modal fade" id="add-courier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            {{-- <form id="update-courier" action="/accounts-update" method="POST"> --}}
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary">Update Details</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-4">
                        <label>Unique Number</label>
                        <h5 class="unique_number">UJJ029921</h5>
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
                        <label for="status" class="form-label">Dispatch Date</label>
                        <input type="Date" name="dispatch_date" class="form-control" required>
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
            let loan_ids = [];
            let goldloan_ids = [];
            let aof_ids = [];
            let dtrf_ids = [];

            $('input.loan:checked').each(function () {
                loan_ids.push($(this).data('id'));
            });
            $('input.goldloan:checked').each(function () {
                goldloan_ids.push($(this).data('id'));
            });
            $('input.aof:checked').each(function () {
                aof_ids.push($(this).data('id'));
            });
            $('input.dtrf:checked').each(function () {
                dtrf_ids.push($(this).data('id'));
            });

            if (loan_ids.length > 0 || goldloan_ids.length > 0 || aof_ids.length > 0 || dtrf_ids.length > 0) {
                console.log(loan_ids);
                console.log(goldloan_ids);
                console.log(aof_ids);
                console.log(dtrf_ids);

                $('input[name="loan_ids[]"]').val(loan_ids);
                $('input[name="goldloan_ids[]"]').val(goldloan_ids);
                $('input[name="aof_ids[]"]').val(aof_ids);
                $('input[name="dtrf_ids[]"]').val(dtrf_ids);
                return false;
                // $('#proceed').submit();
            } else {
                Swal.fire({
                    title: "Warning!",
                    text: "Please select at least one Document.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
                return false;
            }
        });
    });
</script>

@endsection


