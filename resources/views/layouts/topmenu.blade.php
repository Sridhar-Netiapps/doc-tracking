<div class="bg-new">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <ul class="d-flex justify-content-center align-items-center list-unstyled m-0">
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','new') }}">New Documents</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','all') }}">All Documents</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.proceed') }}">Selected Documents</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','rejected') }}">Rejected Documents</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','received') }}">Received Documents</a>
                </li>
                {{-- @hasanyrole('master|bo-maker|bo-checker') --}}
                <li class="nav-item px-4">
                    <a class="nav-link" @hasanyrole('master|ro-user') href="{{ route('dispatches','list') }}" @else href="{{ route('dispatches','ready') }}" @endhasanyrole>Dispatches</a>
                </li>
                {{-- @endhasanyrole
                @hasanyrole('master|ro-user')
                @endhasanyrole --}}
            </ul>
        </div>
    </div>
</div>