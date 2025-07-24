@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
@php
    $type = 'all';
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h3>Reports</h3>
        </div>
        <div class="col-1"></div>
    </div>
</div>
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="filter-bg">
                <form method="POST" action="{{ route('reports') }}">
                    @csrf
                    <div class="row">
                        <div class="col-3">
                            <label for="doc_type">Document Type</label>
                            <select id="doc_type" name="doc_type" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="goldloan">Gold Loan</option>
                                <option value="loan">MB Loan</option>
                                <option value="aof">Liablities</option>
                                <option value="dtrf">DTR Files</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label>Region</label>
                            <select id="region" name="region" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="1">South</option>
                                <option value="2">North</option>
                                <option value="3">East</option>
                                <option value="4">West</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label>Branch Code</label>
                            <input type="text" name="branch_code" class="form-control">
                        </div>
                        <div class="col-3">
                            <label>Branch Name</label>
                            <input type="text" name="branch_name" class="form-control">
                        </div>
                    </div>
                    <div id="dynamic-fields">
                        <div class="row">
                            {{-- CIF / Account --}}
                            <div class="col-3 doc-fields loan goldloan aof">
                                <label>CIF ID</label>
                                <input type="text" name="cif_id" class="form-control">
                            </div>
                            <div class="col-3 doc-fields loan goldloan aof">
                                <label>Account Number</label>
                                <input type="text" name="account_number" class="form-control">
                            </div>
                            <div class="col-3 doc-fields loan goldloan aof">
                                <label>Customer Name</label>
                                <input type="text" name="customer_name" class="form-control">
                            </div>
                
                            <div class="col-3 doc-fields loan goldloan aof">
                                <label>Account Creation Date</label>
                                <input type="date" name="account_creation_date" class="form-control">
                            </div>
                
                            {{-- DTRF Fields --}}
                            <div class="col-3 doc-fields dtrf">
                                <label>DTR File Date</label>
                                <input type="date" name="dtr_file_date" class="form-control">
                            </div>
                            <div class="col-3 doc-fields dtrf">
                                <label>Barcode</label>
                                <input type="text" name="barcode" class="form-control">
                            </div>
                
                            {{-- Unique Fields --}}
                            <div class="col-3 doc-fields goldloan loan">
                                <label>Loan Amount</label>
                                <input type="text" name="loan_amount" class="form-control">
                            </div>
                            <div class="col-3 doc-fields goldloan">
                                <label>Channel (Gold Loan)</label>
                                <input type="text" name="channel_goldloan" class="form-control">
                            </div>
                            <div class="col-3 doc-fields loan">
                                <label>Channel (GL/IL)</label>
                                <input type="text" name="channel_loan" class="form-control">
                            </div>
                            <div class="col-3 doc-fields loan">
                                <label>Loan Cycle</label>
                                <input type="text" name="loan_cycle" class="form-control">
                            </div>
                            <div class="col-3 doc-fields loan">
                                <label>Glow Application ID</label>
                                <input type="text" name="glow_app_id" class="form-control">
                            </div>
                            <div class="col-3 doc-fields loan">
                                <label>Loan Disbursement Type</label>
                                <input type="text" name="loan_disbursement_type" class="form-control">
                            </div>
                            <div class="col-3 doc-fields aof">
                                <label>Scheme</label>
                                <input type="text" name="scheme" class="form-control">
                            </div>
                            <div class="col-3 doc-fields aof">
                                <label>Channel (Swagat/HHD/CRM)</label>
                                <input type="text" name="channel_aof" class="form-control">
                            </div>
                            <div class="col-3 doc-fields aof">
                                <label>PGK No</label>
                                <input type="text" name="pgk_no" class="form-control">
                            </div>
                            <div class="col-3 doc-fields aof">
                                <label>Account Opening Type</label>
                                <input type="text" name="aof_type" class="form-control">
                            </div>
                            {{-- @foreach ([
                                'awb_pod' => 'AWB/POD',
                                'courier_name' => 'Courier Name',
                                'dispatch_date' => 'Dispatch Date',
                                'dispatched_by' => 'Dispatched By (User ID)',
                                'courier_received_date' => 'Courier Received Date @ Mail Room',
                                'tracked_by' => 'Tracked By (User ID)',
                                'remarks' => 'Remarks (Received / Rejected)',
                                'rejection_reason' => 'Reason for Rejection',
                                'lot_no' => 'Lot No',
                                'category' => 'Document Category',
                                'work_order_no' => 'Work Order No.',
                                'vendor_name' => 'Vendor Name',
                                'vendor_movement_date' => 'Date of Vendor Movement',
                                'file_barcode' => 'File Barcode Against Lot',
                                'box_barcode' => 'Box Barcode No.',
                                'addition_date' => 'Date of Addition to Vendor Data',
                                'status' => 'Status (In / Out / Permout / Destroyed)'
                            ] as $field => $label)
                                <div class="col-3">
                                    <label>{{ $label }}</label>
                                    <input type="text" name="{{ $field }}" class="form-control">
                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3">Download Report</button>
                </form>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
<script>
    function toggleFields() {
        var docType = $('#doc_type').val();
        $('.doc-fields').hide();
        if (docType) {
            $('.' + docType).show();
        }
    }

    $(document).ready(function () {
        toggleFields(); // trigger on page load
        $('#doc_type').on('change', toggleFields); // re-trigger on change
    });
</script>
@endsection
