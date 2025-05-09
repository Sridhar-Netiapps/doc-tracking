@if (!(request()->segment(1) === 'documents' && request()->segment(2) === 'proceed'))
<div class="bg-new">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <ul class="d-flex justify-content-center align-items-center list-unstyled m-0">
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','new') }}">New Accounts</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','all') }}">All Accounts</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('dispatches','ready') }}">Dispatches</a>
                </li>
            </ul>
        </div>
    </div>
</div>
@endif
