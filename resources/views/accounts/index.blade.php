@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="d-flex page-heading">
                <h3>In Draft</h3>
                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Filters</button>
            </div>
        </div>
    </div>  
</div>

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Selected Documents <span class="badge text-bg-warning">{{$allDocuments != Null ?count($allDocuments):0}}</span></button>
                </li>
                @unless(auth()->user()->hasAnyRole(['ro-user', 'ro-supervisor', 'bank-user']))
                <li class="ms-auto">
                    <button class="btn btn-primary proceed" type="button">Proceed to Dispatch</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    {{-- <a class="btn btn-secondary" href="{{ url()->previous() }}">Go Back</a> --}}
                </li>
                @endunless
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr> 
                                    @unless(auth()->user()->hasAnyRole(['ro-user', 'ro-supervisor', 'bank-user']))
                                    <th scope="col" class="text-nowrap"><input type="checkbox" class="select_all"/> </th>
                                    @endunless  
                                    <th scope="col" class="text-nowrap"> Document Type</th>
                                    <th scope="col" class="text-nowrap"> Unique Number</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                    <th scope="col" class="text-nowrap"> Region</th>
                                    <th scope="col" class="text-nowrap"> Branch Name</th>
                                    @endunless
                                    <th scope="col" class="text-nowrap"> Branch Code</th>
                                    <th scope="col" class="text-nowrap"> CIF ID</th>
                                    <th scope="col" class="text-nowrap"> Account Number</th>
                                    <th scope="col" class="text-nowrap"> Loan Cycle</th>
                                    <th scope="col" class="text-nowrap"> Loan Amount</th>
                                    <th scope="col" class="text-nowrap"> Barcode</th>
                                    <th scope="col" class="text-nowrap"> Glow App ID</th>
                                    <th scope="col" class="text-nowrap"> Scheme</th>
                                    <th scope="col" class="text-nowrap"> Customer Name</th>
                                    <th scope="col" class="text-nowrap"> Disb Date /<br> Creation Date</th>
                                    <th scope="col" class="text-nowrap"> Channel</th>
                                    <th scope="col" class="text-nowrap"> Disb Type <br>/ Type</th>
                                    <th scope="col" class="text-nowrap"> Business Category</th>
                                    <th scope="col" class="text-nowrap"> Status</th>
                                    <th scope="col" class="text-nowrap"> Activity Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allDocuments as $doc)
                                    <tr>
                                        @unless(auth()->user()->hasAnyRole(['ro-user', 'ro-supervisor', 'bank-user']))
                                        <td><input type="checkbox" class="select" name="doc_ids[]" data-id="{{ $doc->id }}" data-doc_type="{{ $doc->doc_type }}"></td>  
                                        @endunless
                                        <td>
                                            @if ($doc->doc_type == 'loan')
                                                MB Loan
                                            @elseif ($doc->doc_type == 'goldloan')
                                                Gold Loan
                                            @elseif ($doc->doc_type == 'aof')
                                                Liablities
                                            @elseif ($doc->doc_type == 'dtrf')
                                                DTR File
                                            @endif
                                        </td>
                                        <td>{{ $doc->unique_ref_no ?? '-' }}</td>
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                        <td>{{ $doc->region ?? '-' }}</td>
                                        <td>{{ $doc->branch_name ?? '-' }}</td>
                                        @endunless
                                        <td>{{ $doc->branch_code ?? '-' }}</td>
                                        <td>{{ $doc->cif_id ?? '-' }}</td>
                                        <td>{{ $doc->account_number ?? '-' }}</td>
                                        <td>{{ $doc->loan_cycle ?? '-' }}</td>
                                        <td>{{ $doc->loan_amount ?? '-' }}</td>
                                        <td>{{ $doc->barcode ?? '-' }}</td>
                                        <td>{{ $doc->glow_application_id ?? '-' }}</td>
                                        <td>{{ $doc->scheme ?? '-' }}</td>
                                        <td>{{ $doc->customer_name ?? '-' }}</td>
                                        <td>{{ date('d-m-Y', strtotime($doc->account_creation_date)) ?? '-' }}</td>
                                        <td>{{ $doc->channel ?? '-' }}</td>
                                        <td>{{ $doc->loan_disbursement_type ?? $doc->type_of_account_opening ?? '-' }}</td>
                                        {{-- <td>{{ date('d-m-Y', strtotime($doc->account_creation_date)) ?? '-' }}</td> --}}
                                        <td>{{ $doc->business_category ?? '-' }}</td>
                                        <td>{{ $doc->statusName->name ?? '-' }}</td>
                                        <td>{{ date('d-m-Y', strtotime($doc->updated_at)) ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <h5>Filters</h5>
        <form method="POST" action="{{ route('document.filter') }}">
            @csrf
            <div class="row">
                <div class="col-12 mt-3">
                    <select class="form-select document_type" name="document_type">
                        <option value="">Select Document Type</option>
                        <option value="loan" {{ ($filters['document_type'] ?? '') == 'loan' ? 'selected' : '' }}>MB Loan Documents</option>
                        <option value="goldloan" {{ ($filters['document_type'] ?? '') == 'goldloan' ? 'selected' : '' }}>Gold Loan Documents</option>
                        <option value="aof" {{ ($filters['document_type'] ?? '') == 'aof' ? 'selected' : '' }}>Liabilities Documents</option>
                        <option value="dtrf" {{ ($filters['document_type'] ?? '') == 'dtrf' ? 'selected' : '' }}>DTR Files</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control unique_ref_no alphanumeric" placeholder="Unique Number" value="{{ old('unique_ref_no', $filters['unique_ref_no'] ?? '') }}" name="unique_ref_no">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ro-user', 'ro-supervisor']))
                <div class="col-12 mt-3">
                    <select class="form-select region" name="region">
                        <option value="">Select Region</option>
                        <option value="South" {{ ($filters['region'] ?? '') == 'South' ? 'selected' : '' }}>South</option>
                        <option value="North" {{ ($filters['region'] ?? '') == 'North' ? 'selected' : '' }}>North</option>
                        <option value="East" {{ ($filters['region'] ?? '') == 'East' ? 'selected' : '' }}>East</option>
                        <option value="West" {{ ($filters['region'] ?? '') == 'West' ? 'selected' : '' }}>West</option>
                    </select>
                </div>
                {{-- @endunless --}}
                <div class="col-12 mt-3">
                    <input type="number" class="form-control branch_code" placeholder="Branch Code" value="{{ old('branch_code', $filters['branch_code'] ?? '') }}" name="branch_code" min="0">
                </div>
                {{-- @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker'])) --}}
                <div class="col-12 mt-3">
                    <input type="text" class="form-control branch_name lettersonly" placeholder="Branch Name" value="{{ old('branch_name', $filters['branch_name'] ?? '') }}" name="branch_name">
                </div>
                @endunless
                <div class="col-12 mt-3">
                    <input type="search" class="form-control cif_id alphanumeric" 
                           placeholder="CIF ID" 
                           value="{{ old('cif_id', $filters['cif_id'] ?? '') }}" 
                           name="cif_id">
                </div>
                
                <div class="col-12 mt-3">
                    <input type="search" class="form-control account_number alphanumeric" 
                           placeholder=" Account Number" 
                           value="{{ old('account_number', $filters['account_number'] ?? '') }}" 
                           name="account_number">
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="number" class="form-control loan_cycle" placeholder="Loan Cycle" value="{{ old('loan_cycle', $filters['loan_cycle'] ?? '') }}" name="loan_cycle" min="0">
                </div>
                <div class="col-12 mt-3 d-none">
                    <select class="form-select scheme" name="scheme">
                        <option value="">Select Scheme</option>
                        <option value="GL" {{ ($filters['scheme'] ?? '') == 'GL' ? 'selected' : '' }}>GL</option>
                        <option value="IL" {{ ($filters['scheme'] ?? '') == 'IL' ? 'selected' : '' }}>IL</option>
                    </select>
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="text" class="form-control customer_name lettersonly" placeholder="Customer Name" value="{{ old('customer_name', $filters['customer_name'] ?? '') }}" name="customer_name">
                </div>
                    <div class="col-12 mt-3">
                        <input type="text" readonly class="form-control flatpickr-date" placeholder="Date From" value="{{ old('from_date', $filters['from_date'] ?? '') }}" name="from_date">
                    </div>
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Date To" value="{{ old('to_date', $filters['to_date'] ?? '') }}" name="to_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control channel lettersonly" placeholder="Channel" value="{{ old('channel', $filters['channel'] ?? '') }}" name="channel">
                </div>
                <div class="col-12 mt-3 d-none">
                    <select class="form-select" name="type">
                        <option value="">Loan Disbursement/Account Opening</option>
                        <option value="Esign" {{ ($filters['type'] ?? '') == 'Esign' ? 'selected' : '' }}>Esign</option>
                        <option value="Manual" {{ ($filters['type'] ?? '') == 'Manual' ? 'selected' : '' }}>Manual</option>
                    </select>
                </div>
                <div class="col-12 mt-3 d-none">
                    <input type="date" class="form-control" placeholder="DTR File Date" value="{{ old('dtr_file_date', $filters['dtr_file_date'] ?? '') }}" name="dtr_file_date">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control lettersonly" placeholder="Business Category" value="{{ old('business_category', $filters['business_category'] ?? '') }}" name="business_category">
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filt $('.proceed').click(function () {
                        selectedDocuments = $('input.select:checked').map(function () {
                            return {
                                id: $(this).data('id'),
                                doc_type: $(this).data('doc_type')
                            };
                        }).get();
            
                        if (selectedDocuments.length) {
                            // $('#add-courier').modal('show');
                            const formData = {
                                _token: $('input[name="_token"]').val(),
                                loan_ids: [],
                                goldloan_ids: [],
                                dtrf_ids: [],
                                aof_ids: []
                            };
                            $.post({{ route('courier.update')}}, formData)
                                .done(function () {
                                    Swal.fire({
                                        title: "Success!",
                                        text: "Courier details updated successfully.",
                                        icon: "success",
                                        confirmButtonText: "OK"
                                    }).then(() => {
                                        selectedDocuments.forEach(doc => {
                                            $('input.select[data-id="' + doc.id + '"]').closest('tr').remove();
                                            $('span.badge').text(doc_count - selectedDocuments.length);
                                        });
                                        if ($('input.select').length === 0) {
                                            window.location.href = `{{ route('dispatches','ready')}}`;
                                        
                                        }
                                    });
                            })
                        } else {
                            Swal.fire({
                                title: "Warning!",
                                text: "Please select at least one Document.",
                                icon: "warning",
                                confirmButtonText: "OK"
                            });
                        }
                    });er</button>
                    {{-- <a  href="{{ route('accounts.index','all') }}" class="btn btn-secondary">Clear</a> --}}
                    <a href="{{ route('dispatches.clear', $type ?? 'all') }}" class="btn btn-secondary">Clear</a>                
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="add-courier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="update-courier" action="{{ route('courier.update')}}" method="POST">
                @csrf
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary">Update Details</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Courier Name *</label>
                        <select id="courier_name" name="courier_name" class="form-control select2" required>
                            <option value=''>Select</option>
                            @foreach($couriers as $key => $courier)
                                <option value='{{ $key }}'>{{ $courier }}</option>
                            @endforeach
                        </select>
                        <label id="courier_name-error" class="error" for="designation_ids"></label>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">AWB/POD</label>
                        <input type="text" name="awb_pod" class="form-control alphanumeric awb_pod">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">MMRP Barcode No *</label>
                        <input type="text" name="mmrp_barcode" class="form-control alphanumeric" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary btn-lg"><strong>Submit</strong></button>
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d",
            maxDate: "today",         
            allowInput: false,         
            clickOpens: true
        });
        $(".select_all").click(function () {
            $(".select").prop('checked', $(this).prop('checked'));
        });

        let selectedDocuments = [];
        
        // $('.proceed').click(function () {
        //     selectedDocuments = $('input.select:checked').map(function () {
        //         return {
        //             id: $(this).data('id'),
        //             doc_type: $(this).data('doc_type')
        //         };
        //     }).get();

        //     if (selectedDocuments.length) {
        //         // $('#add-courier').modal('show');
        //         const formData = {
        //             _token: $('input[name="_token"]').val(),
        //             loan_ids: [],
        //             goldloan_ids: [],
        //             dtrf_ids: [],
        //             aof_ids: []
        //         };
        //         $.post({{ route('courier.update')}}, formData)
        //             .done(function () {
        //                 Swal.fire({
        //                     title: "Success!",
        //                     text: "Courier details updated successfully.",
        //                     icon: "success",
        //                     confirmButtonText: "OK"
        //                 }).then(() => {
        //                     selectedDocuments.forEach(doc => {
        //                         $('input.select[data-id="' + doc.id + '"]').closest('tr').remove();
        //                         $('span.badge').text(doc_count - selectedDocuments.length);
        //                     });
        //                     if ($('input.select').length === 0) {
        //                         window.location.href = `{{ route('dispatches','ready')}}`;
                            
        //                     }
        //                 });
        //         })
        //     } else {
        //         Swal.fire({
        //             title: "Warning!",
        //             text: "Please select at least one Document.",
        //             icon: "warning",
        //             confirmButtonText: "OK"
        //         });
        //     }
        // });

        $(document).on('click', '.proceed', function () {
            let selectedDocuments = $('input.select:checked').map(function () {
                return {
                    id: $(this).data('id'),
                    doc_type: $(this).data('doc_type')
                };
            }).get();

            if (!selectedDocuments.length) {
                Swal.fire("Warning!", "Please select at least one Document.", "warning");
                return;
            }

            const formData = {
                _token: "{{ csrf_token() }}",
                loan_ids: [],
                goldloan_ids: [],
                dtrf_ids: [],
                aof_ids: []
            };

            selectedDocuments.forEach(doc => {
                const type = String(doc.doc_type).toLowerCase();
                if (type === 'loan') formData.loan_ids.push(doc.id);
                else if (type === 'goldloan') formData.goldloan_ids.push(doc.id);
                else if (type === 'dtrf') formData.dtrf_ids.push(doc.id);
                else if (type === 'aof') formData.aof_ids.push(doc.id);
            });

            $.post("{{ route('courier.update') }}", formData)
                .done(function (res) {
                    console.log("Response:", res);
                    Swal.fire("Success!", "Courier created successfully.", "success")
                        .then(() => window.location.href = `{{ route('dispatches', 'ready') }}`);
                })
                .fail(function (xhr) {
                    console.error("Error:", xhr.responseText);
                    Swal.fire("Error!", "Request failed.", "error");
                });
        });


        $('.awb_pod').on('change', function () {
            let awbPod = $(this).val().trim();
            let $input = $(this);

            $('#awb-error').remove(); // remove old error message

            if (awbPod !== '') {
                $.ajax({
                    url: "{{ route('courier.checkAwb') }}",
                    type: "POST",
                    data: {
                        awb_pod: awbPod,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.exists) {
                            // Show error
                            $input.after('<label id="awb-error" class="error text-danger">This AWB/POD number already exists.</label>');
                            
                            // Clear input
                            $input.val('');

                            // Add red border
                            $input.addClass('is-invalid');

                            // Disable submit
                            $('button[type="submit"]').prop('disabled', true);
                        } else {
                            $('#awb-error').remove();
                            $input.removeClass('is-invalid');
                            $('button[type="submit"]').prop('disabled', false);
                        }
                    }
                });
            }
        });

        $('#update-courier').validate({
            rules: {
                awb_pod: {
                    alphanumeric: true,
                    required: true,
                    sanitize: true
                },
                courier_name: {
                    required: true,
                    sanitize: true
                },
                mmrp_barcode: {
                    alphanumeric: true,
                    required: true,
                    sanitize: true
                }
            },
            messages: {
                awb_pod: {
                    required: "AWB/POD is required",
                    alphanumeric: "Only letters and numbers allowed"
                },
                courier_name: {
                    required: "Courier name is required"
                },
                courier_name: {
                    required: "Courier name is required"
                },
                mmrp_barcode: {
                    required: "Barcode is required"
                }

            },
            submitHandler: function (form) {
                var doc_count = parseInt($('span.badge').text());
                const formData = {
                    _token: $('input[name="_token"]').val(),
                    courier_name: $('select[name="courier_name"]').val(),
                    mmrp_barcode: $('input[name="mmrp_barcode"]').val(),
                    awb_pod: $('input[name="awb_pod"]').val(),
                    dispatch_date: $('input[name="dispatch_date"]').val(),
                    loan_ids: [],
                    goldloan_ids: [],
                    dtrf_ids: [],
                    aof_ids: []
                };

                selectedDocuments.forEach(doc => {
                    const key = doc.doc_type + '_ids';
                    if (formData.hasOwnProperty(key)) {
                        formData[key].push(doc.id);
                    }
                });

                $.post($(form).attr('action'), formData)
                    .done(function () {
                        Swal.fire({
                            title: "Success!",
                            text: "Courier details updated successfully.",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            selectedDocuments.forEach(doc => {
                                $('input.select[data-id="' + doc.id + '"]').closest('tr').remove();
                                $('span.badge').text(doc_count - selectedDocuments.length);
                            });
                            if ($('input.select').length === 0) {
                                window.location.href = `{{ route('dispatches','ready')}}`;
                            } else {
                                $('#update-courier')[0].reset();
                                $('#add-courier').modal('hide');
                            }
                        });
                })
                .fail(function () {
                    Swal.fire({
                        title: "Error!",
                        text: "Something went wrong!",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                });
            }
        });
        $('#applyFilter').click(function () {
            let status = $('#status').val()?.trim();
            let search = $('#search').val()?.trim();
            let dateFrom = $('#date_from').val()?.trim();
            let dateTo = $('#date_to').val()?.trim();

            // Add more filter fields if needed

            if (!status && !search && !dateFrom && !dateTo) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please select any filter option.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            } else {
                $('#filterForm').submit(); // or trigger AJAX filtering
            }
        });
    });
</script>
@endsection