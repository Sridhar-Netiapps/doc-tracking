@extends('layouts.app')
@section('content')

<div class="bg-new">
    <div class="container">
        <div class="row justify-content-start align-items-center">
            <div class="col-3">
                <h4>Customer Account Creation</h4>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="row">
        <div class="col-6">
            <h3>Customer Accounts</h3>
        </div>


    </div>
</div>

    <div class="container mt-3">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Selected Accounts <span class="badge text-bg-warning">{{$allDocuments != Null ?count($allDocuments):0}}</span></button>
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

    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Launch demo modal
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-3 shadow">
                            <div class="modal-body p-4 text-center">
                                <h5 class="mb-0">Enable this setting?</h5>
                                <p class="mb-0">You can always change your mind in your account settings.</p>
                            </div>
                            <div class="modal-footer flex-nowrap p-0">
                                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0 border-end"><strong>Yes, enable</strong></button>
                                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 py-3 m-0 rounded-0" data-bs-dismiss="modal">No thanks</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>





<div class="container mt-3">
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="container mt-3">
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>


<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Holy guacamole!</strong> You should check in on some of those fields below.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>

<div class="container">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
        <label class="form-check-label" for="flexCheckDefault">
            Default checkbox
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" checked>
        <label class="form-check-label" for="flexCheckChecked">
            Checked checkbox
        </label>
    </div>

    <div class="form-check">
        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
        <label class="form-check-label" for="flexRadioDefault1">
            Default radio
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
        <label class="form-check-label" for="flexRadioDefault2">
            Default checked radio
        </label>
    </div>
</div>

    <div class="container mt-3">
        <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="..." class="rounded me-2" alt="...">
                    <strong class="me-auto">Bootstrap</strong>
                    <small>11 mins ago</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Hello, world! This is a toast message.
                </div>
            </div>
        </div>
    </div>





@endsection
