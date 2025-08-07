@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h3>Reports</h3>
        </div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        {{-- <div class="col-1"></div>
            <div class="col-10"> --}}
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                {{-- @if ($loan_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? 'loan') == 'loan' ? 'active':''}} " id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan-tab-pane" type="button" role="tab" aria-controls="loan-tab-pane" aria-selected="true">Loan Documents <span class="badge text-bg-warning">{{$loan_document != Null ?count($loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($gold_loan_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'goldloan' ? 'active':''}}" id="goldloan-tab" data-bs-toggle="tab" data-bs-target="#goldloan-tab-pane" type="button" role="tab" aria-controls="goldloan-tab-pane" aria-selected="false">Gold Loan Documents <span class="badge text-bg-warning">{{$gold_loan_document != Null ?count($gold_loan_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($account_opening_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'aof' ? 'active':''}}" id="aof-tab" data-bs-toggle="tab" data-bs-target="#aof-tab-pane" type="button" role="tab" aria-controls="aof-tab-pane" aria-selected="false">AOF Documents <span class="badge text-bg-warning">{{$account_opening_document != Null ?count($account_opening_document):0}}</span></button>
                </li>
                {{-- @endif --}}
                {{-- @if ($dtrf_document) --}}
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{($filters['document_type'] ?? '') == 'dtrf' ? 'active':''}}" id="dtrf-tab" data-bs-toggle="tab" data-bs-target="#dtrf-tab-pane" type="button" role="tab" aria-controls="dtrf-tab-pane" aria-selected="false">DTRF Documents <span class="badge text-bg-warning">{{$dtrf_document != Null ?count($dtrf_document):0}}</span></button>
                </li>                    
                {{-- @endif --}}
                @hasanyrole('ro-user')
                <li class="ms-auto">
                    <button id="update-all" class="btn btn-primary d-none">Update All</button>
                </li>
                @endhasanyrole
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="loan-tab-pane" role="tabpanel" aria-labelledby="loan-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Unique Number</th>
                                <th scope="col">Branch Code</th>
                                <th scope="col">Branch Name</th>
                                <th scope="col">CIF ID</th>
                                <th scope="col">A/C No</th>
                                <th scope="col">Loan Cycle</th>
                                <th scope="col">Loan Amount</th>
                                <th scope="col">Barcode</th>
                                <th scope="col">Glow Application ID</th>
                                <th scope="col">Customer Name</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Channel</th>
                                <th scope="col">Type of Loan<br>Disbursement</th>
                                <th scope="col">Business Category</th>
                                <th scope="col">Lot No</th>
                                <th scope="col">Document Category</th>
                                <th scope="col">Work Order No</th>
                                <th scope="col">Vendor Name</th>
                                <th scope="col">Date of  Vendor Movement</th>
                                <th scope="col">File Barcode</th>
                                <th scope="col">Box Barcode</th>
                                <th scope="col">Date of addition to Vendor Data</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($loan_document)
                                @foreach ($loan_document as $row)
                                <tr class="doc-row">
                                        <td>{{ $row->unique_ref_no }}</td>
                                        <td>{{ $row->branch_code }}</td>
                                        <td>{{ $row->branch_name }}</td>
                                        <td>{{ $row->cif_id }}</td>
                                        <td>{{ $row->account_number }}</td>
                                        <td>{{ $row->loan_cycle }}</td>
                                        <td>{{ $row->loan_amount }}</td>
                                        <td>{{ $row->barcode }}</td>
                                        <td>{{ $row->glow_application_id }}</td>
                                        <td>{{ $row->customer_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                        <td>{{ $row->channel }}</td>
                                        <td>{{ $row->loan_disbursement_type }}</td>
                                        <td>{{ $row->business_category }}</td>
                                        <td>{{ $row->lot_no }}</td>
                                        <td>{{ $row->category_of_document }}</td>
                                        <td>{{ $row->work_order_no }}</td>
                                        <td>{{ $row->vendor_name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                        <td>{{ $row->file_barcode }}</td>
                                        <td>{{ $row->box_barcode }}</td>
                                        <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}</td>
                                        <td><button type="submit" data-id="{{ $row->id }}" data-type="loan" class="btn btn-primary retrive" data-bs-toggle="modal" data-bs-target="#doc-retrive">Restore</button></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="goldloan-tab-pane" role="tabpanel" aria-labelledby="goldloan-tab" tabindex="0">
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
                                    <th scope="col" class="text-nowrap">Lot No</th>
                                    <th scope="col" class="text-nowrap">Document Category</th>
                                    <th scope="col" class="text-nowrap">Work Order No</th>
                                    <th scope="col" class="text-nowrap">Vendor Name</th>
                                    <th scope="col" class="text-nowrap">Date of  Vendor Movement</th>
                                    <th scope="col" class="text-nowrap">File Barcode</th>
                                    <th scope="col" class="text-nowrap">Box Barcode</th>
                                    <th scope="col" class="text-nowrap">Date of addition to Vendor Data</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($gold_loan_document)
                                    @foreach ($gold_loan_document as $row)
                                        <tr>
                                            <td>{{ $row->unique_ref_no }}</td>  
                                            <td>{{ $row->branch_code }}</td>
                                            <td>{{ $row->branch_name }}</td>
                                            <td>{{ $row->cif_id }}</td>
                                            <td>{{ $row->account_number }}</td>
                                            <td>{{ $row->customer_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td>{{ $row->channel }}</td>
                                            <td>{{ $row->loan_amount }}</td>
                                            <td>{{ $row->barcode }}</td>
                                            <td>{{ $row->business_category }}</td> 
                                            <td>{{ $row->lot_no }}</td>
                                            <td>{{ $row->category_of_document }}</td>
                                            <td>{{ $row->work_order_no }}</td>
                                            <td>{{ $row->vendor_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                            <td>{{ $row->file_barcode }}</td>
                                            <td>{{ $row->box_barcode }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                            <td>{{ $row->statusName->name ?? '-' }}</td>
                                            <td>
                                                @if ($row->status != 11)
                                                <button type="submit" data-id="{{ $row->id }}" data-type="goldloan" class="btn btn-primary restore">Restore</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="aof-tab-pane" role="tabpanel" aria-labelledby="aof-tab" tabindex="0">
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
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">PGK No</th>
                                    <th scope="col" class="text-nowrap">Type of Account Opening</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Lot No</th>
                                    <th scope="col" class="text-nowrap">Document Category</th>
                                    <th scope="col" class="text-nowrap">Work Order No</th>
                                    <th scope="col" class="text-nowrap">Vendor Name</th>
                                    <th scope="col" class="text-nowrap">Date of  Vendor Movement</th>
                                    <th scope="col" class="text-nowrap">File Barcode</th>
                                    <th scope="col" class="text-nowrap">Box Barcode</th>
                                    <th scope="col" class="text-nowrap">Date of addition to Vendor Data</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($account_opening_document)
                                    @foreach ($account_opening_document as $row)
                                        <tr>
                                            <td>{{ $row->unique_ref_no }}</td>
                                            <td>{{ $row->branch_code }}</td>
                                            <td>{{ $row->branch_name }}</td>
                                            <td>{{ $row->cif_id }}</td>
                                            <td>{{ $row->account_number }}</td>
                                            <td>{{ $row->customer_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->account_creation_date)) }}</td>
                                            <td>{{ $row->channel }}</td>
                                            <td>{{ $row->scheme }}</td>
                                            <td>{{ $row->barcode }}</td>
                                            <td>{{ $row->pgk_no }}</td>
                                            <td>{{ $row->type_of_account_opening }}</td>
                                            <td>{{ $row->business_category }}</td>
                                            <td>{{ $row->lot_no }}</td>
                                            <td>{{ $row->category_of_document }}</td>
                                            <td>{{ $row->work_order_no }}</td>
                                            <td>{{ $row->vendor_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                            <td>{{ $row->file_barcode }}</td>
                                            <td>{{ $row->box_barcode }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                            <td>{{ $row->statusName->name ?? '-' }}</td>
                                            <td>
                                                @if ($row->status != 11)
                                                <button type="submit" data-id="{{ $row->id }}" data-type="aof" class="btn btn-primary restore">Restore</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="dtrf-tab-pane" role="tabpanel" aria-labelledby="dtrf-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-nowrap">Unique Number</th>
                                    <th scope="col" class="text-nowrap">Branch Code</th>
                                    <th scope="col" class="text-nowrap">Branch Name</th>
                                    <th scope="col" class="text-nowrap">DTR File Date</th>
                                    <th scope="col" class="text-nowrap">Business Category</th>
                                    <th scope="col" class="text-nowrap">Lot No</th>
                                    <th scope="col" class="text-nowrap">Barcode</th>
                                    <th scope="col" class="text-nowrap">Document Category</th>
                                    <th scope="col" class="text-nowrap">Work Order No</th>
                                    <th scope="col" class="text-nowrap">Vendor Name</th>
                                    <th scope="col" class="text-nowrap">Date of  Vendor Movement</th>
                                    <th scope="col" class="text-nowrap">File Barcode</th>
                                    <th scope="col" class="text-nowrap">Box Barcode</th>
                                    <th scope="col" class="text-nowrap">Date of addition to Vendor Data</th>
                                    <th scope="col" class="text-nowrap">Status</th>
                                    <th scope="col" class="text-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($dtrf_document)
                                    @foreach ($dtrf_document as $row)
                                        <tr>
                                            <td>{{ $row->unique_ref_no }}</td>
                                            <td>{{ $row->branch_code }}</td>
                                            <td>{{ $row->branch_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->account_creation_date))}}</td>
                                            <td>{{ $row->business_category}}</td>
                                            <td>{{ $row->lot_no }}</td>
                                            <td>{{ $row->barcode }}</td>
                                            <td>{{ $row->category_of_document }}</td>
                                            <td>{{ $row->work_order_no }}</td>
                                            <td>{{ $row->vendor_name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->vendor_movement_date)) }}</td>
                                            <td>{{ $row->file_barcode }}</td>
                                            <td>{{ $row->box_barcode }}</td>
                                            <td>{{ date('d-m-Y', strtotime($row->date_added_to_vendor)) }}</td>
                                            <td>{{ $row->statusName->name ?? '-' }}</td>
                                            <td>
                                                @if ($row->status != 11)
                                                <button type="submit" data-id="{{ $row->id }}" data-type="dtrf" class="btn btn-primary restore">Restore</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        {{-- </div>
        <div class="col-1"></div> --}}
    </div>
</div>
<script>
    $(document).ready(function () {
        $('.restore').click(function (e) {
            var id = $(this).data('id');
            var type = $(this).data('type');

            Swal.fire({
                title: '<h5 class="mb-0 text-primary">Reason Required</h5>',
                input: "text",
                inputLabel: "Enter reason for restore the document:",
                inputPlaceholder: "Reason...",
                showCancelButton: true,
                confirmButtonText: '<b>Confirm Restore</b>',
                cancelButtonText: "Cancel",
                customClass: {
                    popup: 'rounded-3 shadow',
                    confirmButton: 'btn btn-primary btn-lg',
                    cancelButton: 'btn btn-secondary btn-lg',
                },
                inputValidator: value => !value && "Reason is required!"
            }).then(result => {
                if (result.isConfirmed) {
                    $.post(`{{ route('document.restore') }}`, {
                        _token: $('input[name="_token"]').val(),
                        id: id,
                        type: type,
                        reason: result.value,
                    })
                    .done(() => {
                        // Swal.fire("Deleted!", "Document removed successfully.", "success").then(() => {
                        //     $selected.closest('tr').remove();
                        //     // $badge.text(doc_count - $selected.length);
                        //     Object.entries(docMap).forEach(([type, ids]) => {
                        //         const $badge = $(`#${type}-tab`).find('span.badge');
                        //         const current = parseInt($badge.text()) || 0;
                        //         const newCount = Math.max(current - ids.length, 0);
                        //         $badge.text(newCount);
                        //     });
                        // });
                    })
                    .fail(() => {
                        Swal.fire("Error!", "Something went wrong!", "error");
                    });
                }
            });
        });
    });
</script>
@endsection