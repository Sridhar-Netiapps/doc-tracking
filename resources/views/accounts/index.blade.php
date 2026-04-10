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
                @unless(auth()->user()->hasAnyRole(['ro-officer', 'ro-supervisor', 'ho-user', 'branch-user', 'ro-user']))
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
                                    @unless(auth()->user()->hasAnyRole(['ro-officer', 'ro-supervisor', 'ho-user', 'branch-user', 'ro-user']))
                                    <th scope="col" class="text-nowrap"><input type="checkbox" class="select_all"/> </th>
                                    @endunless  
                                    <th scope="col" class="text-nowrap"> Document Type</th>
                                    <th scope="col" class="text-nowrap"> Unique Number</th>
                                    @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
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
                                    <th scope="col" class="text-nowrap"> Disb Type</th>
                                    <th scope="col" class="text-nowrap"> Business Category</th>
                                    <th scope="col" class="text-nowrap"> Status</th>
                                    <th scope="col" class="text-nowrap"> Activity Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allDocuments as $doc)
                                    <tr>
                                        @unless(auth()->user()->hasAnyRole(['ro-officer', 'ro-supervisor', 'ho-user', 'branch-user', 'ro-user']))
                                        <td><input type="checkbox" class="select" name="doc_ids[]" data-id="{{ bin2hex(Crypt::encryptString($doc->id)) }}" data-doc_type="{{ $doc->doc_type }}"></td>  
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
                                        @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'branch-user']))
                                        <td>{{ $doc->region ?? '-' }}</td>
                                        <td>{{ $doc->branch_name ?? '-' }}</td>
                                        @endunless
                                        <td>{{ $doc->branch_code ?? '-' }}</td>
                                        <td>
                                            <span class="secure-data-node"
                                                  data-token="{{ !empty($doc->cif_id) && $doc->cif_id !== '-' ? \App\Helpers\EncryptHelper::generateSecureToken($doc->id, $doc->doc_type, 'cif_id') : '' }}">{{ \App\Helpers\EncryptHelper::maskIdentifier($doc->cif_id ?? '-') }}</span>
                                        </td>
                                        <td>
                                            <span class="secure-data-node"
                                                  data-token="{{ !empty($doc->account_number) && $doc->account_number !== '-' ? \App\Helpers\EncryptHelper::generateSecureToken($doc->id, $doc->doc_type, 'account_number') : '' }}">{{ \App\Helpers\EncryptHelper::maskIdentifier($doc->account_number ?? '-') }}</span>
                                        </td>
                                        <td>{{ $doc->loan_cycle ?? '-' }}</td>
                                        <td>{{ $doc->loan_amount ?? '-' }}</td>
                                        <td>{{ $doc->barcode ?? '-' }}</td>
                                        <td>{{ $doc->glow_application_id ?? '-' }}</td>
                                        <td>{{ $doc->scheme ?? '-' }}</td>
                                        <td>
                                            <span class="secure-data-node"
                                                  data-token="{{ !empty($doc->customer_name) && $doc->customer_name !== '-' ? \App\Helpers\EncryptHelper::generateSecureToken($doc->id, $doc->doc_type, 'customer_name') : '' }}">{{ \App\Helpers\EncryptHelper::maskName($doc->customer_name ?? '-') }}</span>
                                        </td>
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
                    <input type="text" class="form-control unique_ref_no alphanumeric capsonly" placeholder="Unique Number" value="{{ old('unique_ref_no', $filters['unique_ref_no'] ?? '') }}" name="unique_ref_no">
                </div>
                @unless(auth()->user()->hasAnyRole(['bo-maker', 'bo-checker', 'ro-officer', 'ro-supervisor', 'ro-user', 'branch-user']))
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
                    <input type="search" class="form-control cif_id alphanumeric capsonly" 
                           placeholder="CIF ID" 
                           value="{{ \App\Helpers\EncryptHelper::maskIdentifier(old('cif_id', $filters['cif_id'] ?? '')) }}" 
                           name="cif_id">
                </div>
                
                <div class="col-12 mt-3">
                    <input type="search" class="form-control account_number alphanumeric capsonly" 
                           placeholder=" Account Number" 
                           value="{{ \App\Helpers\EncryptHelper::maskIdentifier(old('account_number', $filters['account_number'] ?? '')) }}" 
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
                    <input type="text" class="form-control customer_name lettersonly" placeholder="Customer Name" value="{{ \App\Helpers\EncryptHelper::maskName(old('customer_name', $filters['customer_name'] ?? '')) }}" name="customer_name">
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
                    <button type="submit" class="btn btn-primary">Filter</button>
                    {{-- <a  href="{{ route('accounts.index','all') }}" class="btn btn-secondary">Clear</a> --}}
                    <a href="{{ route('dispatches.clear', $type ?? 'all') }}" class="btn btn-secondary">Clear</a>                
                </div>
            </div>
        </form>
    </div>
</div>
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {
        flatpickr(".flatpickr-date", {
            dateFormat: "d-m-Y",        
            maxDate: "today",         
            allowInput: false,         
            clickOpens: true
        });
        $(".select_all").click(function () {
            $(".select").prop('checked', $(this).prop('checked'));
        });

        let selectedDocuments = [];

        $(document).on('click', '.proceed', function () {
            $(this).prop('disabled', true);
            let selectedDocuments = $('input.select:checked').map(function () {
                return {
                    id: $(this).data('id'),
                    doc_type: $(this).data('doc_type')
                };
            }).get();

            if (!selectedDocuments.length) {
                Swal.fire({title: "Warning!", text: "Please select at least one Document.", icon: "warning"}).then(() => location.reload());
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
                    const successMessage = res?.message || "Courier Created successfully.";
                    Swal.fire({title: "Success!", text: successMessage, icon: "success"})
                        .then(() => window.location.href = `{{ route('dispatches', 'ready') }}`);
                })
                .fail(function (xhr) {
                    let errorMessage = "Something went wrong. Please try again.";

                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseText) {
                        try {
                            const parsed = JSON.parse(xhr.responseText);
                            if (parsed && parsed.error) {
                                errorMessage = parsed.error;
                            }
                        } catch (e) {
                            // Keep fallback message when response is not JSON.
                        }
                    }

                    Swal.fire({title: "Error!", text: errorMessage, icon: "error"}).then(() => location.reload());
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
    });
</script>
@endsection
