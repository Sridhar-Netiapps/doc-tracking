@extends('layouts.app')
@section('content')
@include('layouts.topmenu')
<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div class="d-flex page-heading">
                <h3>Dispatches</h3>
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
                    <a href="{{ route('dispatches','ready') }}" class="nav-link {{$type == 'ready' ? 'active':''}}" id="ready-tab" role="tab" aria-controls="ready-tab-pane"  aria-selected="{{ $type == 'ready' ? 'true' : 'false' }}">Ready to Dispatch  @if ($type == 'ready' && $ready_to_dispatch_count != 0)<span class="badge text-bg-warning">{{$ready_to_dispatch_count}}</span>@endif</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','list') }}" class="nav-link {{$type == 'list' ? 'active':''}}" id="list-tab" role="tab" aria-controls="list-tab-pane" aria-selected="{{ $type == 'list' ? 'true' : 'false' }}">Courier Dispatched @if ($type == 'list' && $dispatched_count != 0)<span class="badge text-bg-warning">{{$dispatched_count}}</span>@endif</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','tracking') }}" class="nav-link {{$type == 'tracking' ? 'active':''}}" id="tracking-tab" role="tab" aria-controls="tracking-tab-pane" aria-selected="{{ $type == 'tracking' ? 'true' : 'false' }}">Pending for Tracking @if ($type == 'tracking' && $tracking_count != 0)<span class="badge text-bg-warning">{{$tracking_count}}</span>@endif</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','delivered') }}" class="nav-link {{$type == 'delivered' ? 'active':''}}" id="delivered-tab" role="tab" aria-controls="delivered-tab-pane" aria-selected="{{ $type == 'delivered' ? 'true' : 'false' }}">Tracking Completed  @if ($type == 'delivered' && $delivered_count != 0)<span class="badge text-bg-warning">{{$delivered_count}}</span>@endif</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a href="{{ route('dispatches','reject') }}" class="nav-link {{$type == 'reject' ? 'active':''}}" id="reject-tab" role="tab" aria-controls="reject-tab-pane" aria-selected="{{ $type == 'reject' ? 'true' : 'false' }}">Courier Rejected @if ($type == 'reject' && $reject_count != 0)<span class="badge text-bg-warning">{{$reject_count}}</span>@endif</a>
                </li>
                @if ($type == 'ready')
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'ro-officer', 'ro-supervisor', 'ho-user']))
                <li class="ms-auto">
                    <form method="POST" action="{{ route('dispatched') }}" id="proceed">
                        @csrf
                        {{-- <button class="btn btn-primary proceed" type="button">Add Courier Details</button> --}}
                    </form>
                </li> 
                @endunless
                @endif
                {{-- @if ($type == 'list')
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                <li class="ms-auto">
                    <button id="update-all" class="btn btn-primary d-none">Update All</button>
                </li>
                @endunless
                @endif --}}
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
                <div class="tab-pane fade active show" id="ready-tab-pane" role="tabpanel" aria-labelledby="ready-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if ($type == 'ready')
                                    {{-- @unless(auth()->user()->hasAnyRole(['bo-maker', 'ro-officer', 'ro-supervisor', 'ho-user']))
                                    <th scope="col"><input type="checkbox" class="readytodispatch_all"/></th>
                                    @endunless --}}
                                    @else
                                    <th scope="col">Dispatch No</th>
                                    @endif
                                    <th scope="col">AWB/POD No</th>
                                    <th scope="col">Courier Name</th>
                                    <th scope="col">MMRP Code</th>
                                    <th scope="col">Branch code</th>
                                    {{-- <th scope="col">Region</th> --}}
                                    <th scope="col">No of Documents</th>
                                    {{-- <th scope="col">No of Gold Loan Documents</th>
                                    <th scope="col">No of DTRF Documents</th>
                                    <th scope="col">No of AOF Documents</th> --}}
                                    <th scope="col">Dispatched Date</th>
                                    <th scope="col">Dispatched By</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Activity Date</th>
                                    @if ($type == 'list' || $type == 'tracking')
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                            <th scope="col">Update Status</th>
                                        @endunless
                                    @endif
                                    <th scope="col" class="border-start">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $row)
                                    <tr data-id="{{ $row->id }}" data-dispatch="{{ $row->dispatch_no }}">
                                        @if ($type == 'ready')
                                        {{-- @unless(auth()->user()->hasAnyRole(['bo-maker', 'ro-officer', 'ro-supervisor', 'ho-user']))
                                        <td><input type="checkbox" class="readytodispatch" name="readytodispatch_ids[]" data-id="{{ $row->id }}" data-doc_type="{{ $row->doc_type }}"></td>  
                                        @endunless --}}
                                        @else
                                        <td>{{ $row->dispatch_no }}</td>
                                        @endif
                                        <td>{{ $row->awb_pod ?? '' }}</td>
                                        <td>{{ $row->courierName->name ?? '' }}</td>
                                        <td>{{ $row->mmrp_barcode ?? '' }}</td>
                                        <td>{{ $row->branch_code }}</td>
                                        {{-- <td>{{ $row->region }}</td> --}}
                                        <td><p>MB Loan - {{ $row->loan_ids!= null ? count(explode(',',$row->loan_ids)):0 }}</p>
                                            <p>Gold Loan - {{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</p>
                                            <p>Liabilities - {{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</p>
                                            <p>DTR Files - {{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</p>
                                        </td>
                                        {{-- <td>{{ $row->goldloan_ids!= null ? count(explode(',',$row->goldloan_ids)):0 }}</td>
                                        <td>{{ $row->dtrf_ids!= null ? count(explode(',',$row->dtrf_ids)):0 }}</td>
                                        <td>{{ $row->aof_ids!= null ? count(explode(',',$row->aof_ids)):0 }}</td> --}}
                                        <td>{{ $row->dispatch_date != null ? date('d-m-Y', strtotime($row->dispatch_date)): '-' }}</td>
                                        <td>{{ $row->creator->first_name }}</td>
                                        <td>{{ $row->statusName->name ?? '-' }}
                                            @if ($row->status == 6 || $row->status == 7 )
                                                <small><p>Reason : </p><img src="/images/info_icon.svg"/></small>
                                                <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->comments }}">
                                                <i>{{ \Illuminate\Support\Str::words($row->comments, 2, '...') }}</i>
                                                </span>
                                            @endif
                                            {{-- @if (in_array($row->status, [6,7]))
                                            <span data-bs-toggle="tooltip" data-bs-html="true" data-bs-title="{{ $row->reason }}">
                                                <img src="/images/info_icon.svg"/>
                                            </span>
                                            @endif --}}
                                        </td>
                                        <td>{{ date('d-m-Y', strtotime($row->updated_at)) ?? '-' }}</td>
                                        @if ($type == 'list')
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                            <td>
                                                <select name="remarks" class="form-control select2 remarks" required>
                                                    <option selected value=5>Received</option>
                                                    <option value=7>Received with Query</option>
                                                    <option value=6>Rejected</option>
                                                </select>
                                                <textarea name="reason_for_rejection" class="form-control reason d-none alphanumeric" rows="2"></textarea>
                                            </td>
                                            @endunless
                                        @endif
                                        @if ($type == 'tracking')
                                            @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                            <td>
                                                <select name="remarks" class="form-control select2 remarks" required>
                                                    <option selected value=12>Tracking Completed</option>
                                                </select>
                                                <textarea name="reason_for_rejection" class="form-control reason d-none" rows="2"></textarea>
                                            </td>
                                            @endunless
                                        @endif
                                        <td class="border-start">
                                            {{-- <a href="{{ route('dispatches.edit', $row->id) }}" class="btn btn-primary btn-sm">Edit</a> --}}
                                            <div class="">
                                                <a href="{{ route('dispatches.view',['type'=>$type,'id'=> Crypt::encryptString($row->id)]) }}" class="border-0"><img src="/images/view_icon.svg"/></a>
                                                @if ($type == 'ready')
                                                @hasrole('bo-checker|master')
                                                <button class="btn btn-primary proceed" data-id="{{ Crypt::encryptString($row->id) }}" type="button">Add Courier Details</button>
                                                @endhasrole
                                                @endif
                                                @if ($type == 'tracking')
                                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))
                                                        <button type="button"class="btn btn-sm btn-primary update-row disable-update-btn" data-id="{{ $row->id }}" id="update-btn-{{ $row->id }}">Update</button>
                                                    @endunless
                                                    @hasanyrole('ro-supervisor|admin|master')
                                                        <button data-id="{{ Crypt::encryptString($row->id) }}" class="btn btn-sm btn-success revert-status">Revert Status</button>
                                                    @endhasanyrole
                                                @endif
                                                @if ($type == 'list')
                                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ho-user', 'branch-user', 'ro-user']))                                             
                                                        <button type="button" value="12" class="btn btn-sm btn-primary update-row">Update</button>
                                                    @endunless
                                                    @hasrole('bo-checker|admin|master')
                                                        <button type="button" class="btn btn-sm btn-primary edit-courier" data-id="{{ Crypt::encryptString($row->id) }}" data-courier-name="{{ $row->courier_name }}" data-awb-pod="{{ $row->awb_pod }}" data-mmrp-barcode="{{ $row->mmrp_barcode }}" data-dispatch-date="{{ $row->dispatch_date }}">
                                                            Edit
                                                        </button>
                                                    @endhasrole
                                                @endif
                                            </div>
                                        </td>
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
        <form method="POST" action="{{ route('dispatches.filter', $type) }}">
            @csrf
            <div class="row">
                <div class="col-12 mt-3">
                    <input type="number" class="form-control dispatch_no" placeholder="Dispatch No" value="{{ old('dispatch_no', $filters['dispatch_no'] ?? '') }}" name="dispatch_no" min="0">
                </div>
                <div class="col-12 mt-3">
                    <input type="text" class="form-control awb_pod alphanumeric capsonly" placeholder="AWB/POD No" value="{{ old('awb_pod', $filters['awb_pod'] ?? '') }}" name="awb_pod">
                </div>
                <div class="col-12 mt-3">
                    <select class="form-select" name="courier" id="courierSelect">
                        <option value="">Courier Name</option>
                        @foreach ($couriers as $courier)
                            <option value="{{ $courier->id }}" {{ ($filters['courier'] ?? '') == $courier->id ? 'selected' : '' }}>
                                {{ $courier->name }}
                            </option>
                        @endforeach
                    </select>                  
                </div>
                <div class="col-12 mt-3">
                    <input type="number" class="form-control alphanumeric capsonly" placeholder="MMRP Code" value="{{ old('mmrp_barcode', $filters['mmrp_barcode'] ?? '') }}" name="mmrp_barcode" min="0">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                <div class="col-12 mt-3">
                    <input type="number" class="form-control branch_code" placeholder="Branch Code" value="{{ old('branch_code', $filters['branch_code'] ?? '') }}" name="branch_code" min="0">
                </div>
                @endunless
                <div class="col-12 mt-3">
                    <input type="text" readonly class="form-control flatpickr-date" placeholder="Dispatched Date" value="{{ old('dispatch_date', $filters['dispatch_date'] ?? '') }}" name="dispatch_date">
                </div>
                <div class="col-12 mt-3">
                    <select class="form-select" name="status">
                        <option value="">Select Status</option>
                        <option value="3" {{ ($filters['status'] ?? '') == '3' ? 'selected' : '' }}> Awaiting checker Approval </option>
                        <option value="4" {{ ($filters['status'] ?? '') == '4' ? 'selected' : '' }}> Dispatched </option>
                        <option value="5" {{ ($filters['status'] ?? '') == '5' ? 'selected' : '' }}> Received </option>
                        <option value="6" {{ ($filters['status'] ?? '') == '6' ? 'selected' : '' }}> Rejected </option>
                        <option value="7" {{ ($filters['status'] ?? '') == '7' ? 'selected' : '' }}> Received with query </option>
                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker']))
                        <option value="12" {{ ($filters['status'] ?? '') == '12' ? 'selected' : '' }}> Tracking Completed </option>
                        @endunless
                    </select>                                      
                </div>
                <div class="col-12 d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('dispatches.clear', $type ?? 'all') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="offcanvas offcanvas-bottom" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <h5>Filters</h5>
    </div>
</div>
<div class="modal fade" id="add-courier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="update-courier" action="{{ route('dispatched' )}}" method="POST">
                @csrf
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary">Update Details</h5>
                </div>
                <input type="hidden" name="dispatch_id" />
                <div class="modal-body p-4 row">
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">Courier Name <span class="text-danger">*</span></label>
                        <select id="courier_name" name="courier_name" class="form-control select2" required>
                            <option value=''>Select</option>
                            @foreach($couriers as $courier)
                                <option value='{{ $courier->id }}'>{{ $courier->name }}</option>
                            @endforeach
                        </select>
                        <label id="courier_name-error" class="error" for="designation_ids"></label>
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">AWB/POD</label>
                        <input type="text" name="awb_pod" class="form-control alphanumeric awb_pod capsonly">
                    </div>
                    <div class="col-4 pb-2">
                        <label for="status" class="form-label">MMRP Barcode No <span class="text-danger">*</span></label>
                        <input type="text" name="mmrp_barcode" class="form-control alphanumeric capsonly" required>
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
<div class="modal fade" id="revert-status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3 shadow">
            <form id="revert-courier-status" action="{{ route('courier.revert')}}" method="POST">
                @csrf
                <div class="modal-header p-4 text-center">
                    <h5 class="mb-0 text-primary">Revert Status</h5>
                </div>
                <div class="modal-body p-4 row">
                    <div class="col pb-2">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <input type="hidden" name="dispatch_id" class="revert-reason"/>
                        <select name="status" class="form-select" required>
                            <option value="4">Dispatched</option>
                            <option value="5">Received</option>
                            <option value="7">Received with Query</option>
                            <option value="6">Rejected</option>
                        </select>
                    </div>
                    <div class="col pb-2">
                        <label for="reason" class="form-label">Reason <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control alphanumeric" required>
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
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {
        var count = $('select[name="remarks"]').length;
        if(count > 0){
            $('#update-all').removeClass('d-none');
        }
        $(".readytodispatch_all").click(function () {
            $(".readytodispatch").prop('checked', $(this).prop('checked'));
        });
        // $('#courierSelect').select2({
        //     placeholder: "Courier Name",
        //     width: '100%',
        //     dropdownAutoWidth: true
        // });
        flatpickr(".flatpickr-date", {
            dateFormat: "d-m-Y",        
            maxDate: "today",         
            allowInput: false, 
            clickOpens: true
        });
        $('select[name="remarks"]').change(function () {
            const row = $(this).closest('tr');
            const reasonField = row.find('textarea[name="reason_for_rejection"]');

            if ($(this).val() === '6' || $(this).val() === '7') {
                reasonField.removeClass('d-none');
            } else {
                reasonField.addClass('d-none').val('');
            }
        });

        $(document).on('click', '.proceed', function () {
            $('input[name="dispatch_id"]').val($(this).data('id')),
            
            // Reset form each time
            $('#update-courier')[0].reset();

            // Show modal
            $('#add-courier').modal('show');
        });


        $('#update-courier').validate({
            rules: {
                awb_pod: { alphanumeric: true, sanitize: true },
                courier_name: { required: true, sanitize: true },
                mmrp_barcode: { alphanumeric: true, required: true, sanitize: true }
            },
            messages: {
                courier_name: { required: "Courier name is required" },
                mmrp_barcode: { required: "MMRP Barcode is required" }
            // },
            // submitHandler: async function (form) {
            //     const formData = new FormData(form);
            //     const isUpdate = actionUrl.includes('update-courier');
            //     formData.append('_method', isUpdate ? 'PUT' : 'POST'); 
            //     try {
            //         const response = await $.ajax({
            //             url: actionUrl,
            //             type: 'POST', 
            //             data: formData,
            //             contentType: false,
            //             processData: false,
            //         });
            //         if (response.success) {
            //             await Swal.fire({
            //                 title: "Success!",
            //                 // text: "Courier details saved successfully.",
            //                 text: isUpdate ? "Courier updated successfully." : "Courier created successfully.",
            //                 icon: "success",
            //                 confirmButtonText: "OK"
            //             }).then(() => {
            //                 window.location.href = `{{ route('dispatches','list') }}`;
            //             });
            //         } else {
            //             Swal.fire({title: "Error!", text: "Failed to save courier details.", icon: "error"});
            //         }
            //     } catch (error) {
            //         console.error("AJAX Error:", error);
            //         Swal.fire({title: "Error!", text: "Something went wrong!", icon: "error"});
            //     }
            }
        });
        
        $(document).on('click', '.edit-courier', function () {
            const courierId = $(this).data('id');
            const courierName = $(this).data('courier-name');
            const awbPod = $(this).data('awb-pod');
            const mmrpBarcode = $(this).data('mmrp-barcode');
            const dispatchDate = $(this).data('dispatch-date');

            $('#update-courier')[0].reset();
            $('input[name="dispatch_id"]').val(courierId);
            $('select[name="courier_name"]').val(courierName);
            $('input[name="awb_pod"]').val(awbPod);
            $('input[name="mmrp_barcode"]').val(mmrpBarcode);
            $('input[name="dispatch_date"]').val(dispatchDate);

            $('#add-courier').modal('show');

            $('#update-courier').attr('action', `{{ url('dispatches/update-updateDetails') }}/${courierId}`);

            $('#update-courier').find('input[name="_method"]').remove();
            $('#update-courier').append('<input type="hidden" name="_method" value="PUT">');
        });



        $('.awb_pod').on('focus', function () {
            let courierId = $('#courier_name').val();

            $('#courier-error').remove();

            if (courierId === '') {
                $('#courier_name').after('<label id="courier-error" class="error text-danger">Please select a Courier Name first.</label>');

                $('#courier_name').focus();
            }
        });

        let invalidAwbs = new Set();

        $('.awb_pod').on('input', function () {
            let awbPod = $(this).val().trim();
            let courierId = $('#courier_name').val();
            let $input = $(this);

            $('#awb-error').remove();
            $input.removeClass('is-invalid');
            invalidAwbs.delete($input[0]); 

            if (awbPod !== '' && courierId !== '') {
                $.ajax({
                    url: "{{ route('courier.checkAwb') }}",
                    type: "POST",
                    data: {
                        awb_pod: awbPod,
                        courier_id: courierId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.exists) {
                            $input.after('<label id="awb-error" class="error text-danger">This AWB/POD number already exists for the selected courier.</label>');
                            $input.addClass('is-invalid');
                            invalidAwbs.add($input[0]); 
                        }
                    }
                });
            }
        });

        $('form').on('submit', function () {
            invalidAwbs.forEach(function(inputEl) {
                $(inputEl).val(''); 
            });
        });

        $('#applyFilter').click(function () {
            let status = $('#status').val()?.trim();
            let search = $('#search').val()?.trim();
            let dateFrom = $('#date_from').val()?.trim();
            let dateTo = $('#date_to').val()?.trim();

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

        $('#revert-courier-status').validate({
            rules: {
                status: { required: true },
                reason: { alphanumeric: true, required: true, sanitize: true }
            },
            messages: {
                courier_name: { required: "Status is required" },
                reason: { required: "Reason is required" }
            }
        });
    });

    function collectRowData(row) {
        const id = row.data('id');
        const dispatch = row.data('dispatch');
        const remarks = row.find('.remarks').val();
        const reason = row.find('.reason').val();

        if ((remarks == '6'|| remarks == '7') && !reason.trim()) {
            throw `Reason is required for this Dispatch No: #${dispatch}`;
        }

        return { id, remarks, reason_for_rejection: reason };
    }

    checkDocumentStatuses();

    $('.update-row').on('click', function () {
        if ($(this).prop('disabled')) return; // Prevent if disabled

        const row = $(this).closest('tr');
        let data;

        try {
            data = [collectRowData(row)];
        } catch (err) {
            Swal.fire({title: "Alert!", text: err, icon: "warning"});
            return;
        }

        sendUpdateRequest(data);
    });

    // Reusable function to check status
    function checkDocumentStatuses() {
        $('.disable-update-btn').each(function () {
            let button = $(this);
            let dispatchId = button.data('id');

            $.ajax({
                url: '/dispatches/check-status/' + dispatchId,
                method: 'GET',
                success: function(response) {
                    if (response.disable_update) {
                        button.prop('disabled', true)
                              .css('background-color', '#a9a9a9') 
                              .css('border-color', '#a9a9a9')
                              .attr('title', 'Update disabled: one or more documents have status 4');
                    } else {
                        button.prop('disabled', false)
                              .removeAttr('title')
                              .css('background-color', '')  
                              .css('border-color', '');
                    }
                },
                error: function() {
                    console.error('Status check failed for dispatch ID: ' + dispatchId);
                }
            });
        });
    }

    function sendUpdateRequest(payload) {
        $.ajax({
            url: '{{ route("dispatches.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                updates: payload
            },
            success: function () {
                Swal.fire({title: "Success" , text:  "Update successful", icon: "success"}).then(() => location.reload());
            },
            error: function () {
                Swal.fire({title: "Error!", text: "Update failed!", icon: "error"});
            }
        });
    }

    // Handle bulk update
    $('#update-all').on('click', function () {
        const data = [];
        let hasError = false;

        $('tr[data-id]').each(function () {
            try {
                data.push(collectRowData($(this)));
            } catch (err) {
                Swal.fire({title: "Alert!", text: err, icon: "warning"});
                hasError = true;
                return false; // stop loop
            }
        });

        if (!hasError && data.length) {
            sendUpdateRequest(data);
        }
    });

    // Common AJAX function
    function sendUpdateRequest(payload) {
        $.ajax({
            url: '{{ route("dispatches.update") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                updates: payload
            },
            success: function () {
                Swal.fire({title: "Success" , text:  "Update successful", icon: "success"}).then(() => location.reload());
            },
            error: function () {
                Swal.fire({title: "Error!", text: "Update failed!", icon: "error"});
            }
        });
    }
    $('.revert-status').click(function () {
        $('input.revert-reason').val($(this).data('id'));
        $('#revert-status').modal('show');
    });
</script>

@endsection
