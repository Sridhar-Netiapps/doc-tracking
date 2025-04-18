@extends('layouts.app')
@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-3">
            <h3>{{ ucfirst($type) }} Accounts</h3>
        </div>
        <div class="col-9">
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
                            <!-- Add more -->
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
                    <form method="POST" action="{{ route('accounts.bulkReview') }}" id="selectedForm">
                        @csrf
                        <div class="col-2 mt-3">
                            
                                <button class="btn btn-success btn-sm proceed" type="submit">Proceed with Selected</button>
                            
                        </div>
                </form>
                </div>
            </form>            
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
            <div class="col-10">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="savings-tab" data-bs-toggle="tab" data-bs-target="#savings-tab-pane" type="button" role="tab" aria-controls="savings-tab-pane" aria-selected="true">Savings Accounts <span class="badge text-bg-warning">{{count($accounts['Savings'])}}</span></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="current-tab" data-bs-toggle="tab" data-bs-target="#current-tab-pane" type="button" role="tab" aria-controls="current-tab-pane" aria-selected="false">Current Accounts <span class="badge text-bg-warning">{{count($accounts['Current'])}}</span></button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan-tab-pane" type="button" role="tab" aria-controls="loan-tab-pane" aria-selected="false">Loan Accounts <span class="badge text-bg-warning">{{count($accounts['Loan'])}}</span></button>
                </li>
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="savings-tab-pane" role="tabpanel" aria-labelledby="savings-tab" tabindex="0">
                   
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col"><input type="checkbox" /> </th>
                            <th scope="col">Unique Number</th>
                            {{-- <th scope="col">Region</th> --}}
                            <th scope="col">Branch Code</th>
                            <th scope="col">Branch Name</th>
                            <th scope="col">CIF ID</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Account Creation Date</th>
                            <th scope="col">Channel</th>
                            <th scope="col">PGK No. / Glow application ID</th>
                            <th scope="col">Status</th>
                            {{-- <th scope="col" class="border-start">Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts['Savings'] as $row)
                                <tr>
                                    <td><input type="checkbox" class="account" name="account_ids[]" data-id="{{ $row->id }}"></td>
                                    <td>{{ $row->unique_ref_no }}</td>
                                        <!-- Other table data -->
                                    {{-- </tr>
                                    
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    {{-- <td><input type="checkbox" /></td> --}} 
                                    <td>{{ $row->unique_ref_no }}</td>
                                    {{-- <td>{{ $row->region }}</td> --}}
                                    <td>{{ $row->branch_code }}</td>
                                    <td>{{ $row->branch_name }}</td>
                                    <td>{{ $row->cif_id }}</td>
                                    <td>{{ $row->account_number }}</td>
                                    <td>{{ $row->customer_name }}</td>
                                    <td>{{ $row->account_creation_date }}</td>
                                    <td>{{ $row->channel }}</td>
                                    <td>{{ $row->business_category }}</td>
                                    {{-- <td>{{ $row->barcode }}</td> --}}
                                    {{-- <td>{{ $row->channel }}</td> --}}
                                    {{-- <td>{{ ucwords(str_replace("_"," ",$row->business_type)) }}</td>
                                    <td>{{ ucwords(str_replace("-"," ",$row->rbi_classification)) }}</td> --}}
                                    {{-- <td>{{ $row->branch_office_type }}</td>
                                    <td>{{ $row->pincode }}</td>
                                    <td>{{ $row->city }}</td> --}}
                                    <td>{{ $row->status }}</td>
                                    {{-- <td class="border-start">
                                        <div class="btn-actions"> --}}
                                            {{-- <a href="{{ route('accounts.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a> --}}
                                            {{-- <form action="{{ route('accounts.destroy', $row->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                            </form> --}}
                                        {{-- </div> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="current-tab-pane" role="tabpanel" aria-labelledby="current-tab" tabindex="0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"><input type="checkbox" /> </th>
                            <th scope="col">Unique Number</th>
                            <th scope="col">Branch Code</th>
                            <th scope="col">Branch Name</th>
                            <th scope="col">CIF ID</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Account Creation Date</th>
                            <th scope="col">Channel</th>
                            <th scope="col">PGK No. / Glow application ID</th>
                            <th scope="col">Status</th>
                            {{-- <th scope="col" class="border-start">Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts['Current'] as $row)
                                <tr>
                                    <td><input type="checkbox" class="account" name="account_ids[]" data-id="{{ $row->id }}"></td>
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    {{-- <td><input type="checkbox" /></td>
                                    <td>{{ $row->unique_ref_no }}</td> --}}
                                    {{-- <td>{{ $row->region }}</td> --}}
                                    <td>{{ $row->branch_code }}</td>
                                    <td>{{ $row->branch_name }}</td>
                                    <td>{{ $row->cif_id }}</td>
                                    <td>{{ $row->account_number }}</td>
                                    <td>{{ $row->customer_name }}</td>
                                    <td>{{ $row->account_creation_date }}</td>
                                    <td>{{ $row->channel }}</td>
                                    <td>{{ $row->business_category }}</td>
                                    {{-- <td>{{ $row->barcode }}</td> --}}
                                    {{-- <td>{{ $row->channel }}</td> --}}
                                    {{-- <td>{{ ucwords(str_replace("_"," ",$row->business_type)) }}</td>
                                    <td>{{ ucwords(str_replace("-"," ",$row->rbi_classification)) }}</td> --}}
                                    {{-- <td>{{ $row->branch_office_type }}</td>
                                    <td>{{ $row->pincode }}</td>
                                    <td>{{ $row->city }}</td> --}}
                                    <td>
                                        {{ $row->status }}
                                    </td>
                                    <td class="border-start">
                                        <div class="btn-actions">
                                            <a href="{{ route('accounts.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            {{-- <form action="{{ route('accounts.destroy', $row->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="tab-pane fade" id="loan-tab-pane" role="tabpanel" aria-labelledby="loan-tab" tabindex="0">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"><input type="checkbox" /> </th>
                            <th scope="col">Unique Number</th>
                            <th scope="col">Branch Code</th>
                            <th scope="col">Branch Name</th>
                            <th scope="col">CIF ID</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Account Creation Date</th>
                            <th scope="col">Channel</th>
                            <th scope="col">PGK No. / Glow application ID</th>
                            <th scope="col">Status</th>
                            {{-- <th scope="col" class="border-start">Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts['Loan'] as $row)
                                <tr>
                                    <td><input type="checkbox" class="account" name="account_ids[]" data-id="{{ $row->id }}"></td>
                                    {{-- <td>{{ $loop->iteration }}</td> --}}
                                    {{-- <td><input type="checkbox" /></td> --}}
                                    <td>{{ $row->unique_ref_no }}</td>
                                    {{-- <td>{{ $row->region }}</td> --}}
                                    <td>{{ $row->branch_code }}</td>
                                    <td>{{ $row->branch_name }}</td>
                                    <td>{{ $row->cif_id }}</td>
                                    <td>{{ $row->account_number }}</td>
                                    <td>{{ $row->customer_name }}</td>
                                    <td>{{ $row->account_creation_date }}</td>
                                    <td>{{ $row->channel }}</td>
                                    <td>{{ $row->business_category }}</td>
                                    {{-- <td>{{ $row->barcode }}</td> --}}
                                    {{-- <td>{{ $row->channel }}</td> --}}
                                    {{-- <td>{{ ucwords(str_replace("_"," ",$row->business_type)) }}</td>
                                    <td>{{ ucwords(str_replace("-"," ",$row->rbi_classification)) }}</td> --}}
                                    {{-- <td>{{ $row->branch_office_type }}</td>
                                    <td>{{ $row->pincode }}</td>
                                    <td>{{ $row->city }}</td> --}}
                                    <td>{{ $row->status }}</td>
                                    <td class="border-start">
                                        <div class="btn-actions">
                                            <a href="{{ route('accounts.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            {{-- <form action="{{ route('accounts.destroy', $row->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                            </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="col-1"></div>
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
    $(document).ready(function() {
        $('.proceed').click(function(){
            var ids = [];
            $('input.account:checked').each(function() {
                ids.push($(this).attr('data-id'));
            });
            console.log(ids);
        })
    });
    document.getElementById('selectAll').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('input[name="account_ids[]"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection


