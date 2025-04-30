@extends('layouts.app')
@section('content')
<div class="container-fluid mt-3">
    {{-- <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="filter-bg">
                <form method="POST" action="{{ route('accounts.index',$type) }}">
                    <div class="row">
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="unique_ref_no" placeholder="Unique Ref No" value="{{ request('unique_ref_no') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="branch_name" placeholder="Branch Name" value="{{ request('branch_name') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="branch_code" placeholder="Branch Code" value="{{ request('branch_code') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="cif_id" placeholder="CIF ID" value="{{ request('cif_id') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="text" name="account_number" placeholder="Account Number" value="{{ request('account_number') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <select class="form-control select2" name="region">
                                <option value="">Select Region</option>
                                <option value="South" {{ request('region') == 'South' ? 'selected' : '' }}>South</option>
                                <option value="North" {{ request('region') == 'North' ? 'selected' : '' }}>North</option>
                                <option value="East" {{ request('region') == 'East' ? 'selected' : '' }}>East</option>
                                <option value="West" {{ request('region') == 'West' ? 'selected' : '' }}>West</option>
                            </select>
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="date" placeholder="Unique Ref No" name="from_date" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <input class="form-control" type="date" placeholder="Unique Ref No" name="to_date" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-2 mt-3">
                            <button class="btn btn-secondary" type="reset">Clear</button>
                            <button class="btn btn-primary" type="submit">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-1"></div>
    </div> --}}
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h3>Dispatches</h3>
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
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">New Dispatches <span class="badge text-bg-warning">2300</span></button>
                </li>
                {{-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Accounts list <span class="badge text-bg-warning">200</span></button>
                </li> --}}
                <li class="ms-auto">
                    {{-- <form method="POST" action="{{ route('accounts.proceed') }}" id="proceed"> --}}
                        {{-- @csrf --}}
                        <button class="btn btn-primary proceed" type="button">Proceed to Dispatch</button>
                    {{-- </form> --}}
                </li>
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="select_all"/></th>
                                <th scope="col">AWB/POD Number</th>
                                <th scope="col">Courier Name</th>
                                <th scope="col">No of Loan Documents</th>
                                <th scope="col">No of Gold Loan Documents</th>
                                <th scope="col">No of DTRF Documents</th>
                                <th scope="col">No of AOF Documents</th>
                                <th scope="col">Dispatch Date</th>
                                <th scope="col">Dispatch By</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="border-start">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dispatches as $row)
                                <tr>
                                    <td><input type="checkbox" class="select" name="dispatch_ids[]" data-id="{{ $row->id }}" data-doc_type="{{ $row->doc_type }}"></td>  
                                    <td>{{ $row->awb_pod }}</td>
                                    <td>{{ $row->courier_name }}</td>
                                    <td>{{ $row->loan_ids!= null ? count(explode(',',$row->loan_ids)):0 }}</td>
                                    <td>{{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</td>
                                    <td>{{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</td>
                                    <td>{{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</td>
                                    <td>{{ $row->dispatch_date }}</td>
                                    <td>{{ $row->creator->first_name }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td class="border-start">
                                        {{-- <a href="{{ route('dispatches.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a> --}}
                                        <a href="{{ route('dispatches.view', $row->id) }}" class="btn btn-secondary btn-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                </div>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="update-courier" action="/accounts-update" method="POST">
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary">Upload Vendor Movement Information</h5>
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
                        <label for="status" class="form-label">Lot No.</label>
                        <input type="text" name="lot_no" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Work Order No.</label>
                        <input type="text" name="work_order_no" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Vendor Name</label>
                        <input type="text" name="vendor_name" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Date of Vendor Movement</label>
                        <input type="date" name="vendor_movement_date" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">File barcode againt Lot No.</label>
                        <input type="file" name="file_barcode" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Box Barcode</label>
                        <input type="file" name="box_barcode" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Date of addition vendor Data</label>
                        <input type="date" name="vendor_addition_date" class="form-control" required>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="In">In</option>
                            <option value="Out">Out</option>
                            <option value="Permout">Permout</option>
                            <option value="Destroyed">Destroyed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a href="/accounts-update" class="btn btn-primary btn-lg"><strong>Submit</strong></a>
                    {{-- <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button> --}}
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".select_all").click(function () {
            $(".select").prop('checked', $(this).prop('checked'));
        });
    });
</script>
@endsection
