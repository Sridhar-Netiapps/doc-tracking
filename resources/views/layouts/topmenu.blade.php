<div class="bg-new">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            {{-- <div class="col-3">
                <select class="form-select" data-bs-placement="Select Category">
                    <option>Account Creation</option>
                    <option>Voucher</option>
                    <option>Insurance</option>
                </select>
            </div> --}}
            <div class="treeList h-100">
                <ul>
                    <li class="mb-auto"><a href="{{ route('accounts.index','new') }}">New Accounts</a></li>
                    <li class="mb-auto"><a href="{{ route('accounts.index','all') }}">All Accounts</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>