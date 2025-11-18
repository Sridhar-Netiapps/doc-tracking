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
                <form method="POST" action="{{ route('reports') }}" id="reportForm">
                    @csrf
                    <div class="row">
                        <div class="col mt-2">
                            <label for="doc_type">Document Type <span class="text-danger">*</span></label>
                            <select id="doc_type" name="doc_type" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="loan">MB Loan</option>
                                <option value="goldloan">Gold Loan</option>
                                <option value="aof">Liablities</option>
                                <option value="dtrf">DTR Files</option>
                            </select>
                        </div>
                        <div class="col mt-2">
                            <label for="search_type">Search Criteria</label>
                            <select id="search_type" name="search_type" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="courier">Courier</option>
                                <option value="vendor">Vendor</option>
                            </select>
                        </div>
                        <div class="col mt-2">
                            <label>Region</label>
                            <select id="region" name="region" class="form-select">
                                <option value="">-- Select --</option>
                                <option value="South">South</option>
                                <option value="North">North</option>
                                <option value="East">East</option>
                                <option value="West">West</option>
                            </select>
                        </div>
                        <div class="col mt-2">
                            <label>Branch Code</label>
                            <select id="branch_code" name="branch_code[]" class="form-control select2" multiple>
                                <option value="">Select</option>
                                @foreach ($branches as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="dynamic-fields">
                        <div class="row">
                            {{-- CIF / Account --}}
                            <div class="col-3 mt-2 doc-fields loan goldloan aof">
                                <label>CIF ID</label>
                                <input type="text" name="cif_id" class="form-control alphanumeric capsonly">
                            </div>
                            <div class="col-3 mt-2 doc-fields loan goldloan aof">
                                <label>Account Number</label>
                                <input type="text" id="account_number" name="account_number" class="form-control alphanumeric capsonly">
                            </div>
                            <div class="col-3 mt-2 doc-fields goldloan loan aof">
                                <label>Channel</label>
                                <input type="text" name="channel" class="form-control alphanumeric">
                            </div>

                            @foreach ([
                                'awb_pod' => 'AWB/POD',
                                'courier_name' => 'Courier Name',
                            ] as $field => $label)
                                <div class="col-3 mt-2 courier">
                                    <label>{{ $label }}</label>

                                    @if ($field === 'courier_name')
                                        <select id="courier_name" name="courier_name" class="form-control select2">
                                            <option value="">Select</option>
                                            @foreach ($couriers as $courier)
                                                <option value="{{ $courier->id }}"
                                                    {{ request('courier_name') == $courier->id ? 'selected' : '' }}>
                                                    {{ $courier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" name="{{ $field }}" class="form-control"
                                            value="{{ request($field) }}">
                                    @endif
                                </div>
                            @endforeach

                            @foreach ([
                                'lot_no' => 'Lot No',
                                'category' => 'Document Category',
                                'work_order_no' => 'Work Order No.',
                                'vendor_name' => 'Vendor Name',
                                'file_barcode' => 'File Barcode Against Lot',
                                'box_barcode' => 'Box Barcode No.',
                            ] as $field => $label)
                                <div class="col-3 mt-2 vendor">
                                    <label>{{ $label }}</label>

                                    @if ($field === 'vendor_name')
                                        <select class="form-select" name="vendor_name" required>
                                            <option value="">Select Vendor Name</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->name }}" {{ ($doc->vendor_name ?? '') == $vendor->name ? 'selected' : '' }}>
                                                    {{ $vendor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" name="{{ $field }}" class="form-control" value="{{ $doc->$field ?? '' }}">
                                    @endif
                                </div>
                            @endforeach

                        </div>
                        <div class="row">
                            <div class="col-3 mt-2">
                                <label>From Date</label>
                                <input type="text" id="from_date" readonly class="form-control datepicker" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                            </div>
                            <div class="col-3 mt-2">
                                <label>To Date</label>
                                <input type="text" id="to_date" readonly class="form-control datepicker" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
                            </div>
                            <div class="col-3 mt-2">
                                <label for="date_field">Date Criteria</label>
                                <select id="date_field" name="date_field" class="form-select">
                                    <option value="">-- Select --</option>
                                    <option value="creation_date">Account Creation / DTR File Date</option>
                                    <option value="dispatch_date">Dispatch Date</option>
                                    <option value="received_date">Courier Received Date</option>
                                    <option value="movement_date">Vendor Movement Date</option>
                                    <option value="addition_date">Addition to Vendor Data</option>
                                    <option value="activity_date">Activity Date</option>
                                    <option value="tracking_date">Tracking Date</option>
                                </select>
                            </div>
                            <div class="col-3 mt-2">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status[]" class="form-select select2" multiple>
                                    <option value="">-- Select --</option>
                                    <option value="1">Pending</option>
                                    <option value="2">In Draft</option>
                                    <option value="3">Awaiting checker Approval</option>
                                    <option value="4">Dispatched</option>
                                    <option value="5">Received</option>
                                    <option value="6">Rejected</option>
                                    <option value="7">Received with query</option>
                                    <option value="8">IN</option>
                                    <option value="9">OUT</option>
                                    <option value="10">Permount</option>
                                    <option value="11">Destroyed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-3">Download Report</button>
                </form>
            </div>
        </div>
        <div class="col-1"></div>
    </div>
</div>
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {
        function docfields() {
            var docType = $('#doc_type').val();
            var search_type = $('#search_type').val();
            $('.doc-fields').hide();
            $('.courier').hide();
            $('.vendor').hide();
            if (docType) {
                $('.' + docType).show();
            }
            if (search_type) {
                $('.' + search_type).show();
            }
        }

        $(".datepicker").flatpickr({
                dateFormat: "d-m-Y",
                allowInput: true
        });

        $('.select2').select2();
        docfields();
        $('#doc_type').on('change', docfields);
        $('#search_type').on('change', docfields);
        $('#reportForm').validate({
            rules: {
                account_number: {
                    alphanumeric: {
                        depends: function () {
                            return $('#account_number').val().trim() !== '';
                        }
                    },
                    sanitize: true
                },
                date_field: {
                    required: function () {
                        return $.trim($('#from_date').val()) !== '' || $.trim($('#to_date').val()) !== '';
                    },
                    sanitize: true
                },
                from_date: {
                    customDate: true,
                    sanitize: true
                },
                to_date: {
                    customDate: true,
                    greaterThanOrEqual: "#from_date",
                    sanitize: true
                }
            },
            messages: {
                account_number: {
                    alphanumeric: "Only letters and numbers allowed"
                },
                date_field: {
                    required: "Date Criteria is required"
                },
                to_date: {
                    greaterThanOrEqual: "To Date must be greater than or equal to From Date"
                }
            },
            errorClass: 'is-invalid',
            errorElement: 'div',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                error.insertAfter(element);
            },
            invalidHandler: function (event, validator) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
