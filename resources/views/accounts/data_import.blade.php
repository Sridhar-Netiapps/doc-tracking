@extends('layouts.admin')
@section('content')
<div class="rightPanel h-100">
    <div class="d-flex justify-content-between align-items-center mb-2 headerTitle">
        <div>
            <div class="d-flex justify-content-center align-items-center">
                <h3 class="me-3">Import Data</h3>
            </div>
        </div>
</div>
<div class="container-fluid mt-3">
    <div class="row h-100">
        <div class="col-6">
            <div class="form-card">
                <form id="rma-upload" action="{{ route('uploads.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header text-center">
                        <h5 class="mb-0 text-primary" id="modal-title">Upload Data</h5>
                    </div>
                    <div class="modal-body">
                        <div class="col mt-2">
                            <label for="doc_type">Document Type <span class="text-danger">*</span></label>
                            <select id="doc_type" name="doc_type" class="form-select" required>
                                <option value="">-- Select --</option>
                                <option value="loan">MB Loan</option>
                                <option value="goldloan">Gold Loan</option>
                                <option value="aof">Liablities</option>
                                <option value="dtrf">DTR Files</option>
                            </select>
                        </div>
                        <label for="excel_file" class="form-label">Upload File <span class="text-danger">*</span></label> 
                        {{-- <a href="{{ route('vendor.sample.download') }}" class="btn btn-link"> Download Sample File </a> --}}
                        <input type="file" name="excel_file" class="form-control file-validate"  data-ext="csv,xls,xlsx" required> 
                        <label class="text-danger mt-3" id="excel_file-error"></label>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary btn-lg me-3"><strong>Submit</strong></button>
                        <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(session('upload_failures'))
        <div class="alert alert-danger">
            <h5>Import Failures:</h5>
            <ul>
                @foreach(session('upload_failures') as $failure)
                    <li>
                        Row {{ $failure->row() }} - {{ implode(', ', $failure->errors()) }}
                        @if($failure->values())
                            <br><small>Data: {{ json_encode($failure->values()) }}</small>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
<script nonce='{{ env("CSP_NONCE") }}'>
    $(document).ready(function () {   
        $("#rma-upload").validate({
                rules: {
                    doc_type: { required: true, sanitize: true },
                    excel_file: { required: true, sanitize: true }
            },
            messages: {
                doc_type: { required: "Document Type is required" },
                excel_file: { required: "File is required" }
            }
        });
    });
</script>
@endsection