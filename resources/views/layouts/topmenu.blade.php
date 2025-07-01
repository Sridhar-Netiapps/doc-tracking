@php
    $currentTab = Request::segment(2); // gets 'all', 'pending', etc.
@endphp

<div class="bg-new">
    <div class="container-fluid">
        <div class="row justify-content-start align-items-center">
            <ul class="d-flex justify-content-center align-items-center list-unstyled m-0">
                <li class="nav-item px-4">
                    <a class="nav-link {{ $currentTab === 'all' ? 'active-tab' : '' }}" href="{{ route('accounts.index','all') }}">All</a>
                </li>
                
                <li class="nav-item px-4">
                    <a class="nav-link {{ $currentTab === 'pending' ? 'active-tab' : '' }}" href="{{ route('accounts.index','pending') }}">Pending</a>
                </li>
                
                <li class="nav-item px-4">
                    <a class="nav-link {{ Request::is('documents/proceed') ? 'active-tab' : '' }}" href="{{ route('accounts.proceed') }}">In Draft</a>
                </li>
                
                <li class="nav-item px-4">
                    <a class="nav-link 
                        @hasanyrole('master|ro-user')
                            {{ Request::segment(1) === 'dispatches' && Request::segment(2) === 'list' ? 'active-tab' : '' }}
                        @else
                            {{ Request::segment(1) === 'dispatches' && Request::segment(2) === 'ready' ? 'active-tab' : '' }}
                        @endhasanyrole
                    " href="@hasanyrole('master|ro-user'){{ route('dispatches','list') }}@else{{ route('dispatches','ready') }}@endhasanyrole">Dispatches</a>
                </li>
                
                <li class="nav-item px-4">
                    <a class="nav-link {{ $currentTab === 'rejected' ? 'active-tab' : '' }}" href="{{ route('accounts.index','rejected') }}">Rejected</a>
                </li>
                
                <li class="nav-item px-4">
                    <a class="nav-link {{ $currentTab === 'received' ? 'active-tab' : '' }}" href="{{ route('accounts.index','received') }}">Received</a>
                </li>
                
                @hasanyrole('master|ro-user')
                <li class="nav-item px-4">
                    <a class="nav-link {{ $currentTab === 'moved' ? 'active-tab' : '' }}" href="{{ route('accounts.index','moved') }}">Moved to RMA</a>
                </li>
                @endhasanyrole
                
                {{-- @hasanyrole('master|bo-maker|bo-checker') --}}
                {{-- @endhasanyrole --}}
            </ul>
        </div>
    </div>
</div>