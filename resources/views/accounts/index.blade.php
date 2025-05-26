@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="filter-bg">
                <form method="POST" action="{{ route('document.filter') }}">
                    @csrf
                    <div class="row">
                        <div class="col-2 mt-3">
                            <select class="form-select document_type" name="document_type">
                                <option value="">Select Document Type</option>
                                <option value="loan" {{ request('document_type') == 'loan' ? 'selected' : '' }}>MB Loan Documents</option>
                                <option value="gold_loan" {{ request('document_type') == 'gold_loan' ? 'selected' : '' }}>Gold Loan Documents</option>
                                <option value="aof" {{ request('document_type') == 'aof' ? 'selected' : '' }}>Liablities Documents</option>
                                <option value="dtrf" {{ request('document_type') == 'dtrf' ? 'selected' : '' }}>DTR Files</option>
                            </select>
                        </div>
                        <div class="col-2 mt-3">
                            <input type="text" class="form-control unique_ref_no" placeholder="Unique Number" value="{{ old('unique_ref_no', $filters['unique_ref_no'] ?? '') }}" name="unique_ref_no">
                        </div>
                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                        <div class="col-2 mt-3">
                            <select class="form-select region" name="region">
                                <option value="">Select Region</option>
                                <option value="South" {{ request('region') == 'South' ? 'selected' : '' }}>South</option>
                                <option value="North" {{ request('region') == 'North' ? 'selected' : '' }}>North</option>
                                <option value="East" {{ request('region') == 'East' ? 'selected' : '' }}>East</option>
                                <option value="West" {{ request('region') == 'West' ? 'selected' : '' }}>West</option>
                            </select>
                        </div>
                        @endunless
                        <div class="col-2 mt-3">
                            <input type="text" class="form-control branch_code" placeholder="Branch Code" value="{{ old('branch_code', $filters['branch_code'] ?? '') }}" name="branch_code">
                        </div>
                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                        <div class="col-2 mt-3">
                            <input type="text" class="form-control branch_name" placeholder="Branch Name" value="{{ old('branch_name', $filters['branch_name'] ?? '') }}" name="branch_name">
                        </div>
                        @endunless
                        <div class="col-2 mt-3 d-none">
                            <input type="text" class="form-control cif_id" placeholder="CIF ID" value="{{ old('cif_id', $filters['cif_id'] ?? '') }}" name="cif_id">
                        </div>
                        <div class="col-2 mt-3 d-none">
                            <input type="text" class="form-control account_number" placeholder="Account Number" value="{{ old('account_number', $filters['account_number'] ?? '') }}" name="account_number">
                        </div>
                        <div class="col-2 mt-3 d-none">
                            <input type="number" class="form-control loan_cycle" placeholder="Loan Cycle" value="{{ old('loan_cycle', $filters['loan_cycle'] ?? '') }}" name="loan_cycle">
                        </div>
                        <div class="col-2 mt-3 d-none">
                            <select class="form-select scheme" name="scheme">
                                <option value="">Select Scheme</option>
                                <option value="GL" {{ request('scheme') == 'GL' ? 'selected' : '' }}>GL</option>
                                <option value="IL" {{ request('scheme') == 'IL' ? 'selected' : '' }}>IL</option>
                            </select>
                        </div>
                        <div class="col-2 mt-3 d-none">
                            <input type="text" class="form-control customer_name" placeholder="Customer Name" value="{{ old('customer_name', $filters['customer_name'] ?? '') }}" name="customer_name">
                        </div>
                        <div class="col-2 mt-3">
                            <input type="text" class="form-control datepicker account_creation_date" placeholder="Account Creation Date" value="{{ old('account_creation_date', $filters['account_creation_date'] ?? '') }}" name="account_creation_date">
                        </div>
                        <div class="col-2 mt-3 d-none">
                            <input type="text" class="form-control channel" placeholder="Channel" value="{{ old('channel', $filters['channel'] ?? '') }}" name="channel">
                        </div>
                        <div class="col-2 mt-3">
                            <select class="form-select" name="type">
                                <option value="">Loan Disbursement/Account Opening</option>
                                <option value="Esign" {{ request('type') == 'Esign' ? 'selected' : '' }}>Esign</option>
                                <option value="Manual" {{ request('type') == 'Manual' ? 'selected' : '' }}>Manual</option>
                            </select>
                        </div>
                        <div class="col-2 mt-3 d-none">
                            <input type="date" class="form-control" placeholder="DTR File Date" value="{{ old('dtr_file_date', $filters['dtr_file_date'] ?? '') }}" name="dtr_file_date">
                        </div>
                        <div class="col-2 mt-3">
                            <input type="text" class="form-control" placeholder="Business Category" value="{{ old('business_category', $filters['business_category'] ?? '') }}" name="business_category">
                        </div>
                        <div class="col-2 mt-3">
                            <select class="form-select" placeholder="Status" value="{{ old('status', $filters['status'] ?? '') }}" name="status">
                                <option value="">Select Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Dispatched">Dispatched</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
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
                    <button class="btn btn-primary proceed" type="button">Add Courier Details</button>
                    <a class="btn btn-secondary" href="{{ url()->previous() }}">Go Back</a>
                </li>
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <table class="table table-striped">
                        <thead>
                            <tr> 
                                <th scope="col"><input type="checkbox" class="select_all"/> </th>     
                                <th scope="col"> Document Type</th>
                                <th scope="col"> Unique Number</th>
                                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                <th scope="col"> Region</th>
                                <th scope="col"> Branch Name</th>
                                @endunless
                                <th scope="col"> Branch Code</th>
                                <th scope="col"> CIF ID</th>
                                <th scope="col"> Account Number</th>
                                <th scope="col"> Loan Cycle</th>
                                <th scope="col"> Scheme</th>
                                <th scope="col"> Customer Name</th>
                                <th scope="col"> Account Creation Date</th>
                                <th scope="col"> Channel</th>
                                <th scope="col"> Loan Disbursement Type / Account Opening</th>
                                {{-- <th scope="col"> DTR File Date</th> --}}
                                <th scope="col"> Business Category</th>
                                <th scope="col"> Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allDocuments as $doc)
                                <tr>
                                    <td><input type="checkbox" class="select" name="doc_ids[]" data-id="{{ $doc->id }}" data-doc_type="{{ $doc->doc_type }}"></td>  
                                    <td>{{ ucfirst($doc->doc_type) }}</td>
                                    <td>{{ $doc->unique_ref_no ?? '-' }}</td>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                                    <td>{{ $doc->region ?? '-' }}</td>
                                    <td>{{ $doc->branch_name ?? '-' }}</td>
                                    @endunless
                                    <td>{{ $doc->branch_code ?? '-' }}</td>
                                    <td>{{ $doc->cif_id ?? '-' }}</td>
                                    <td>{{ $doc->account_number ?? '-' }}</td>
                                    <td>{{ $doc->loan_cycle ?? '-' }}</td>
                                    <td>{{ $doc->scheme ?? '-' }}</td>
                                    <td>{{ $doc->customer_name ?? '-' }}</td>
                                    <td>{{ $doc->account_creation_date ?? '-' }}</td>
                                    <td>{{ $doc->channel ?? '-' }}</td>
                                    <td>{{ $doc->loan_disbursement_type ?? $doc->type_of_account_opening ?? '-' }}</td>
                                    {{-- <td>{{ $doc->account_creation_date ?? '-' }}</td> --}}
                                    <td>{{ $doc->business_category ?? '-' }}</td>
                                    <td>{{ ucfirst($doc->status) ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
                    <div class="col-6 pb-2">
                        <label for="status" class="form-label">Courier Name *</label>
                        <select id="courier_name" name="courier_name" class="form-control select2" required>
                            <option value=''>Select</option>
                            @foreach($couriers as $key => $courier)
                                <option value='{{ $key }}'>{{ $courier }}</option>
                            @endforeach
                        </select>
                        <label id="courier_name-error" class="error" for="designation_ids"></label>
                        {{-- <input type="text" name="courier_name" class="form-control" required> --}}
                    </div>
                    <div class="col-6 pb-2">
                        <label for="status" class="form-label">AWB/POD</label>
                        <input type="text" name="awb_pod" class="form-control">
                    </div>
                    <div class="w-100"></div> 
                    <div class="col-6 pb-2">
                        <label for="dispatch_date" class="form-label">Dispatch Date</label>
                        <input type="text" class="form-control datepicker dispatch_date" value="{{ request('dispatch_date') }}" name="dispatch_date" id="dispatch_date" required>
                    </div>
                                        
                    
                    <div class="col-6 pb-2">
                        <label for="status" class="form-label">MMRP Barcode No. *</label>
                        <input type="text" name="mmrp_barcode" class="form-control" required>
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
        $(".select_all").click(function () {
            $(".select").prop('checked', $(this).prop('checked'));
        });

        let selectedDocuments = [];

        $('.proceed').click(function () {
            selectedDocuments = $('input.select:checked').map(function () {
                return {
                    id: $(this).data('id'),
                    doc_type: $(this).data('doc_type')
                };
            }).get();

            if (selectedDocuments.length) {
                $('#add-courier').modal('show');
            } else {
                Swal.fire({
                    title: "Warning!",
                    text: "Please select at least one Document.",
                    icon: "warning",
                    confirmButtonText: "OK"
                });
            }
        });

        $('#update-courier').validate({
            rules: {
                awb_pod: {
                    alphanumeric: true,
                    sanitize: true
                },
                courier_name: {
                    required: true,
                    sanitize: true
                },
                dispatch_date: {
                    required: true,
                    sanitize: true
                },
                mmrp_barcode: {
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

        // $('#approval_type').change(function () {
        //     var type = $(this).val();
        //     $('#gl_account_no').val($(this).find(':selected').data('acc_no'));
        //     if (type == '1' || type == '2') {
        //         $('.CDI-group').removeClass('d-none');
        //         $('.EPD-group').addClass('d-none');
        //         $('.TPB-group').addClass('d-none');
        //         $('.CRW-group').addClass('d-none');
        //         $('.EPT-group').addClass('d-none');
        //         $('.OE-group').addClass('d-none');
        //         $('.OT-group').addClass('d-none');
        //         $('.FY-group').addClass('d-none');
        //         $('.OLR-group').addClass('d-none');
        //         $('option.7').addClass('d-none');
        //         $('option.0').removeClass('d-none');
        //         $('label[for="reversal_type"]').html('Request for');
        //     }
        //     else if (type == '3' || type == '4') {
        //         $('.CDI-group').addClass('d-none');
        //         $('.EPD-group').removeClass('d-none');
        //         $('.TPB-group').addClass('d-none');
        //         $('.CRW-group').addClass('d-none');
        //         $('.EPT-group').addClass('d-none');
        //         $('.OE-group').addClass('d-none');
        //         $('.OT-group').addClass('d-none');
        //         $('.FY-group').addClass('d-none');
        //         $('.OLR-group').addClass('d-none');
        //         $('option.7').addClass('d-none');
        //         $('option.0').removeClass('d-none');
        //         $('label[for="reversal_type"]').html('Request for');
        //     }
        //     else if (type == '5' || type == '6') {
        //         $('.CDI-group').addClass('d-none');
        //         $('.EPD-group').addClass('d-none');
        //         $('.TPB-group').removeClass('d-none');
        //         $('.CRW-group').addClass('d-none');
        //         $('.EPT-group').addClass('d-none');
        //         $('option.7').addClass('d-none');
        //         $('option.0').removeClass('d-none');
        //         $('.OE-group').addClass('d-none');
        //         $('.OT-group').addClass('d-none');
        //         $('.FY-group').addClass('d-none');
        //         $('.OLR-group').addClass('d-none');
        //         $('label[for="reversal_type"]').html('Request for');
        //     }
        //     else if (type == '7') {
        //         $('.CDI-group').addClass('d-none');
        //         $('.EPD-group').addClass('d-none');
        //         $('.TPB-group').addClass('d-none');
        //         $('.OE-group').addClass('d-none');
        //         $('.OT-group').addClass('d-none');
        //         $('option.7').removeClass('d-none');
        //         $('option.0').addClass('d-none');
        //         $('label[for="reversal_type"]').html('Request for Approval');
        //         $('.CRW-group').removeClass('d-none');
        //         $('.EPT-group').addClass('d-none');
        //         $('.FY-group').addClass('d-none');
        //         $('.OLR-group').addClass('d-none');
        //     }
        //     else if (type == '8' || type == '9') {
        //         $('.CDI-group').addClass('d-none');
        //         $('.EPD-group').addClass('d-none');
        //         $('.TPB-group').addClass('d-none');
        //         $('.CRW-group').addClass('d-none');
        //         $('.OE-group').addClass('d-none');
        //         $('.OT-group').addClass('d-none');
        //         $('option.7').addClass('d-none');
        //         $('option.0').removeClass('d-none');
        //         $('.EPT-group').removeClass('d-none');
        //         $('label[for="reversal_type"]').html('Request for');
        //         $('.FY-group').addClass('d-none');
        //         $('.OLR-group').addClass('d-none');
        //     }
        //     else if (type == '10') {
        //         $('.CDI-group').addClass('d-none');
        //         $('.EPD-group').removeClass('d-none');
        //         $('.TPB-group').addClass('d-none');
        //         $('.CRW-group').addClass('d-none');
        //         $('.OE-group').addClass('d-none');
        //         $('.OT-group').addClass('d-none');
        //         $('option.7').addClass('d-none');
        //         $('option.0').removeClass('d-none');
        //         $('.EPT-group').removeClass('d-none');
        //         $('.FY-group').removeClass('d-none');
        //         $('.OLR-group').removeClass('d-none');
        //         $('label[for="reversal_type"]').html('Request for');
        //     }
        // });
    });
</script>
@endsection