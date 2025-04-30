@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <h3>Dispatched Documents</h3>
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
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Dispatched Documents <span class="badge text-bg-warning">{{$allDocuments != Null ?count($allDocuments):0}}</span></button>
                </li>
                <li class="ms-auto">
                    {{-- <button class="btn btn-primary proceed" type="button">Add Courier Details</button> --}}
                    <a class="btn btn-secondary" href="{{ route('dispatches')}}">Go Back</a>
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
                                <th scope="col"> Region</th>
                                <th scope="col"> Branch Code</th>
                                <th scope="col"> Branch Name</th>
                                <th scope="col"> CIF ID</th>
                                <th scope="col"> Account Number</th>
                                <th scope="col"> Loan Cycle</th>
                                <th scope="col"> Scheme</th>
                                <th scope="col"> Customer Name</th>
                                <th scope="col"> Account Creation Date</th>
                                <th scope="col"> Channel</th>
                                <th scope="col"> Loan Disbursement Type / Account Opening</th>
                                <th scope="col"> DTR File Date</th>
                                <th scope="col"> Business Category</th>
                                {{-- <th scope="col"> Status</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allDocuments as $doc)
                                <tr>
                                    <td><input type="checkbox" class="select" name="doc_ids[]" data-id="{{ $doc->id }}" data-doc_type="{{ $doc->doc_type }}"></td>  
                                    <td>{{ ucfirst($doc->doc_type) }}</td>
                                    <td>{{ $doc->unique_ref_no ?? '-' }}</td>
                                    <td>{{ $doc->region ?? '-' }}</td>
                                    <td>{{ $doc->branch_code ?? '-' }}</td>
                                    <td>{{ $doc->branch_name ?? '-' }}</td>
                                    <td>{{ $doc->cif_id ?? '-' }}</td>
                                    <td>{{ $doc->account_number ?? '-' }}</td>
                                    <td>{{ $doc->loan_cycle ?? '-' }}</td>
                                    <td>{{ $doc->scheme ?? '-' }}</td>
                                    <td>{{ $doc->customer_name ?? '-' }}</td>
                                    <td>{{ $doc->account_creation_date ?? '-' }}</td>
                                    <td>{{ $doc->channel ?? '-' }}</td>
                                    <td>{{ $doc->loan_disbursement_type ?? $doc->type_of_account_opening ?? '-' }}</td>
                                    <td>{{ $doc->dtr_file_date ?? '-' }}</td>
                                    <td>{{ $doc->business_category ?? '-' }}</td>
                                    {{-- <td>{{ $doc->status ?? '-' }}</td> --}}
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
                    {{-- <a href="/accounts-process" class="btn btn-primary btn-lg"><strong>Submit</strong></a> --}}
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

        $('#update-courier').submit(function (e) {
            e.preventDefault();

            let formData = {
                _token: $('input[name="_token"]').val(),
                courier_name: $('input[name="courier_name"]').val(),
                awb_pod: $('input[name="awb_pod"]').val(),
                dispatch_date: $('input[name="dispatch_date"]').val(),
                loan_ids: [],
                goldloan_ids: [],
                dtrf_ids: [],
                aof_ids: []
            };

            selectedDocuments.forEach(doc => {
                if (formData.hasOwnProperty(doc.doc_type + '_ids')) {
                    formData[doc.doc_type + '_ids'].push(doc.id);
                }
            });

            $.post($(this).attr('action'), formData)
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
                            window.location.href = `{{ route('accounts.index',$type)}}`;
                        }
                        else{
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
        });
    });
</script>
@endsection