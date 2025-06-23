<div class="bg-new">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <ul class="d-flex justify-content-center align-items-center list-unstyled m-0">
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','all') }}">All </a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','pending') }}">Pending</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.proceed') }}">In Draft</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" @hasanyrole('master|ro-user') href="{{ route('dispatches','list') }}" @else href="{{ route('dispatches','ready') }}" @endhasanyrole>Dispatches</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','rejected') }}">Rejected</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','received') }}">Received</a>
                </li>
                @hasanyrole('master|ro-user')
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','moved') }}">Moved to RMA</a>
                </li>
                @endhasanyrole
                {{-- @hasanyrole('master|bo-maker|bo-checker') --}}
                {{-- @endhasanyrole --}}
            </ul>
        </div>
    </div>
</div>