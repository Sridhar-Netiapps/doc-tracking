<div class="bg-new">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <ul class="d-flex justify-content-center align-items-center list-unstyled m-0">
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','new') }}">New </a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','all') }}">All </a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.proceed') }}">In Draft</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','rejected') }}">Rejected</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','received') }}">Received</a>
                </li>
                <li class="nav-item px-4">
                    <a class="nav-link" href="{{ route('accounts.index','moved') }}">Moved</a>
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